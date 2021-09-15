<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\NewOrderEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderLongResource;
use App\Http\Resources\OrderResource;
use App\Models\Country;
use App\Models\Distributor;
use App\Models\Gift;
use App\Models\Manager;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\OrderGifts;
use App\Models\OrderProducts;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductPrices;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Notifications\NewOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private $vat = 0;
    private $kilo_cost = 0;
    private $default_speed = 0;
    private $new_registered_users_discount_percent = 0;
    private $new_registered_users_discount_time = 0;
    private $country = null;

    public function __construct()
    {
        $request = request();
        $this->country = $country = Country::find($request->header('country-id'));
        $this->vat = (float)number_format((optional($country)->vat / 100), 2, '.', '');
        $this->kilo_cost = (float)number_format(optional($country)->kilo_cost, 2, '.', '');
        $this->default_speed = (optional($country)->default_speed > 0) ? (float)number_format(optional($country)->default_speed, 2, '.', '') : 100;
        $this->new_registered_users_discount_percent = (float)number_format((optional($country)->new_registered_users_discount_percent / 100), 2, '.', '');
        $this->new_registered_users_discount_time = (float)number_format(optional($country)->new_registered_users_discount_time, 2, '.', '');
    }

    public function rateOrder(Request $request, $id)
    {
        $request->validate(['order_products' => 'array']);
        $user = apiUser();
        $order = Order::where('user_id', $user->id)->findOrFail($id);
        if ($order->is_checkout == false) return apiError(api('Please Check out the previous order'));
        if ($order->status != Order::COMPLETED) return apiError(api('The status of the request does not allow it to be evaluated'), 422);

//        if ($order->is_rated == true) return apiError(api('Order Rated Previously'));
        $order->update([
            'delivery_speed' => $request->delivery_speed,
            'attitude' => $request->attitude,
            'respond_time' => $request->respond_time,
            'is_rated' => true,
        ]);
        foreach ($request->order_products as $item) {
            $order_product = OrderProducts::where('order_id', $order->id)->findOrFail($item['id']);
            $order_product->update(['rate' => $item['rate']]);
            $product_price = $order_product->product_price;
            $rate = OrderProducts::where('price_id', $product_price->id)->avg('rate');
            optional($product_price)->product->update(['rate' => $rate]);
        }
        return apiSuccess(new OrderResource($order));

    }

    public function checkOrder(Request $request)
    {
//        if ($user->orders()->notCheckout()->count() > 0) return apiError(api('Please Check out the previous order'));
        $result = $this->getOrderDetails($request);
        $result = collect($result)->except('distributor');
        return apiSuccess($result);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'paid_type' => 'required|in:' . Order::CASH . ',' . Order::ONLINE,
        ]);
        $checkGifts = $this->checkGifts($request);
        if (isset($checkGifts)) return apiError($checkGifts);
        $result = $this->getOrderDetails($request);
        $user = apiUser();
        if ($user->orders()->pendingOrOnWayOrder()->count() > 0) return apiError(api("You can not make more than one pending order"));
        //Create Order
        $order = DB::transaction(function () use ($request, $result) {
            $distributor_data = optional(optional($result)['distributor'])->distributor;
            if (isset($distributor_data)) optional($distributor_data)->update(['total_earned' => ($distributor_data->total_earned + optional($result)['total'])]);

            //Create Order
            $order = Order::query()->create([
                'user_id' => optional(apiUser())->id,
                'type' => Order::USER,
                'status' => Order::PENDING,
                'is_checkout' => true,
                'has_chat' => false,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'paid_type' => $request->paid_type,
                'exact_location' => $request->exact_location,
                'is_rated' => false,
                'products_cost' => $result['sub_total'],
                'distance' => ($result['passedMax']) ? 0 : $result['distance'],
                'vat' => $result['vat'],
                'delivery_time' => ($result['passedMax']) ? null : $result['delivery_time'],
                'vat_cost' => $result['vat_cost'],
                'shipping_cost' => ($result['passedMax']) ? 0 : $result['shipping_charges'],
                'discount' => $result['discount'],
                'total_cost' => $result['total'],
                'distributor_id' => ($result['passedMax']) ? null : optional(optional($result)['distributor'])['id'],
                'note' => $request->get('note', null),
            ]);
            $meals_cost = 0;
            $gift_points_total = 0;

            foreach ($request->get('products', []) as $key => $item) {
                $type = optional($item)['type'];
                if ($type == 'gift') {
                    $gift_quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                    $gift = Gift::query()->find($item['id']);
                    if ($gift) {
                        $gift_points = $gift->points;
                        $gift_points_total += ($gift_points * $gift_quantity);
                        $order_item = OrderGifts::create([
                            'order_id' => $order->id,
                            'gift_id' => $gift->id,
                            'quantity' => $gift_quantity,
                            'points' => $gift_points,
                            'amount' => $gift_points_total,
                        ]);
                    }
                } else {
                    $meal_quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                    $meal_price = ProductPrices::query()->find($item['id']);
                    if ($meal_price) {
                        $price = $meal_price->price;
                        $meals_cost += $price * $meal_quantity;
                        $order_item = OrderProducts::create([
                            'order_id' => $order->id,
                            'price_id' => $meal_price->id,
                            'quantity' => $meal_quantity,
                            'price' => $meal_price->piece_cost,
                            'amount' => $meal_price->price * $meal_quantity,
                        ]);
                    }
                }
            }

            $payment = $order->payments()->create([
                'user_id' => $order->user_id,
                'amount' => $order->total_cost,
            ]);
            return $order;
        });
        $order['passedMax'] = $result['passedMax'];
//        TODO check notification error
        $distributor = $result['distributor'];
        if (isset($distributor) && !$order['passedMax']) \Illuminate\Support\Facades\Notification::send($result['distributor'], new NewOrderNotification($order));
        else \Illuminate\Support\Facades\Notification::send(Manager::get(), new NewOrderNotification($order));

        return apiSuccess(new OrderResource($order));
    }

    public function search_another_distributor(Request $request, $id)
    {
        $order = Order::where('status', Order::PENDING)->findOrFail($id);
        $distributor = optional($this->getRandomDistributor());
        $distributor_data = optional($distributor)->distributor;
        optional($distributor_data)->update(['total_earned' => ($distributor_data->total_earned + $order->total_cost)]);
        $order->update(['distributor_id' => optional($distributor)->id]);
        return apiSuccess(new OrderResource($order), api('Distributor Linked Successfully'));
    }


    public function orders(Request $request)
    {
        $request['except_arr_resource'] = ['items'];

        $user = apiUser();
        $status = $request->get('status', false);
        $order_id = request()->get('order_id', false);

        if ($order_id) {
            $order = Order::where('user_id', $user->id)
                ->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                })->findOrFail($order_id);

            return apiSuccess(new OrderResource($order));
        } else {

            $orders = Order::where('user_id', $user->id)
                ->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                })->paginate($this->perPage);

            return apiSuccess([
                'items' => OrderResource::collection($orders->items()),
                'paginate' => paginate($orders),
            ]);
        }

    }

    public function order(Request $request, $id)
    {
        $request['except_arr_resource'] = ['items'];
        $user = apiUser();
        $order = Order::query()->checkout()->where('user_id', $user->id)->findOrFail($id);
//        return $order;
        return apiSuccess(new OrderLongResource($order));
    }


    public function cancelOrder(Request $request, $id)
    {
        $request['except_arr_resource'] = ['items'];
        $user = apiUser();
        $order = Order::query()->where('user_id', $user->id)->findOrFail($id);
        if ($order->status != Order::PENDING /*|| !$order->paid*/) return apiError(api('Order Can Not be Canceled'), 422);
        $order->update(['status' => Order::CANCELED]);
        return apiSuccess(new OrderResource($order), api('Order Canceled Successfully'));
    }


    private function getDistributorStakeholder()
    {
        $user = apiUser();
        if ($user->user_type == User::MERCHANT) {
            $merchant = optional($user)->merchant;
            return (optional($merchant)->merchant_type == Merchant::MERCHANT_TYPES['RETAILER']) ? Distributor::stakeholders['retailer'] : Distributor::stakeholders['wholesaler'];
        } else {
            return Distributor::stakeholders['users'];
        }
    }

    private function getRandomDistributor()
    {
//        dd(checkRequestIsWorkingOrNot());
        $user = apiUser();
        $lat = request()->get('lat', $user->lat);
        $lng = request()->get('lng', $user->lng);
//        dd($lat,$lng,$user->lat,$user->lng,checkRequestIsWorkingOrNot());
        $range = $this->country->max_distance ?? 100;
//dd($range);
        $range_in_meters = $range * 1000;
        $stakehoder = $this->getDistributorStakeholder();
        $distributor = User::where('user_type', User::DISTRIBUTOR)
            ->currentCountry($this->country->id)
            ->whereHas('distributor', function ($query) use ($stakehoder) {
                $query->where('receive_orders', true)->where('stakeholder_type', $stakehoder);
            })->get()->map(function ($item) use ($range_in_meters, $lat, $lng, $range) {
                $dist_km = distance($item->lat, $item->lng, $lat, $lng);
//               if ($item->id != 89) dd($dist_km, $item->lat, $item->lng, $lat, $lng, $item);
                if ($range >= $dist_km) return $item;
            })->first();
//        dd($distributor);
//            ->when($lat && $lng, function ($query) use ($lat, $lng, $range) {
//                $range_in_meters = $range * 1000;
//                $query
//                    ->selectRaw("users.*,ROUND(6371 * acos( cos( radians({$lat}) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin(radians(lat)) ) ) AS distance")
//                    ->having("distance", "<=", $range_in_meters);
////                    ->orderBy('distance', "ASC");
//            })->inRandomOrder()->first();
//        dd($distributor);

        if (!isset($distributor)) return null;
        $products = request()->products;
        foreach ($products as $index => $product) {
            if ($user->user_type == User::MERCHANT) {
                $price = ProductPrices::find($product['id']);
                if (!isset($price)) return null;
                $quantity = $product['quantity'] * $price->piece_per_package;
            } else {
                $quantity = $product['quantity'];
            }

            $product_price_count = $distributor->distributor_products()
                ->whereHas('price')
                ->where('price_id', $product['id'])->where('remaining', '>=', $quantity)->count();//ProductPrices::where();
//            dd($product_price_count,$products,$quantity);

            if ($product_price_count == 0) return null;
        }
//        $orders = optional(optional(optional($distributor)->orders())->where(function ($query) {
//            $query->pendingOrTimedOutOrOnWayOrder();
//        }))->count();
//
//        $counter = 1;
//        while (!is_null($orders) && $orders != 0) {
//            if ($counter > 10) return null; //loop just 10 times
//            $counter++;
//            $this->getRandomDistributor();
//        }
        return $distributor;
    }


    private function checkGifts(Request $request)
    {
        $user = apiUser();
        $total_points = 0;
        foreach ($request->get('products', []) as $key => $item) {
            $type = optional($item)['type'];
            if ($type == 'gift') {
                $gift = Gift::findOrFail($item['id']);
                $total_points += ($gift->points * $item['quantity']);
            }
        }
        if ($total_points > $user->points) return api('you have no enough points.');

    }

    private function getOrderDetails(Request $request)
    {
        $user = apiUser();
        $vat = $this->vat;
        $meals_cost = 0;
        $distributor = $this->getRandomDistributor();
//        dd($distributor);
        $res = getShippingCostTime($request, $distributor, $this->country);
        $distance = $res['distance'];
        $delivery_cost = $res['delivery_cost'];
        $delivery_time = $res['delivery_time'];
        $passedMax = $res['passedMax'];
        foreach ($request->get('products', []) as $key => $item) {
            if ($item['type'] == 'gift') continue;
            $meal_quantity = isset($item['quantity']) ? $item['quantity'] : 1;
//            $meal = Product::query()->find($item['id']);
            $meal_price = ProductPrices::query()->find($item['id']);
            if ($meal_price) {
                $price = $meal_price->price;
                $meals_cost += $price * $meal_quantity;
            }
        }
        $meals_cost_without_vat = $meals_cost / 1.16;
        $total_vat = $meals_cost_without_vat * 0.16;
        $tax_cost = $meals_cost * $vat;
        $total_cost = $meals_cost + $delivery_cost;

        //        discount for user
        $date = Carbon::parse($user->created_at)->addMonths($this->new_registered_users_discount_time);
        $date_now = Carbon::now();
        if ($date_now <= $date) $discount_cost = $this->new_registered_users_discount_percent * $total_cost;
        else $discount_cost = 0;
        $total_cost -= $discount_cost;
        return [
            'distance' => (float)number_format($distance, 2, '.', ''),
            'sub_total' => (float)number_format(($meals_cost_without_vat), 2, '.', ''),
            'vat' => (float)number_format($this->vat, 2, '.', ''),
            'vat_cost' => (float)number_format($total_vat, 2, '.', ''),
            'shipping_charges' => (float)number_format($delivery_cost, 2, '.', ''),
            'discount' => (float)number_format($discount_cost, 2, '.', ''),
            'total' => (float)number_format($total_cost, 2, '.', ''),
            'delivery_time' => $delivery_time,
            'distributor' => $distributor,
            'passedMax' => $passedMax,
            'has3Orders' => isset($user) ? $user->orders()->completedOrder()->count() >= 3 : false,
        ];
    }
}
