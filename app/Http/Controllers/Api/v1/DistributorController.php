<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;

use App\Http\Resources\Api\v1\Distributor\DistributorProductsResource;
use App\Http\Resources\Api\v1\Distributor\FinancialResource;
use App\Http\Resources\Api\v1\Distributor\OrderResource;
use App\Http\Resources\Api\v1\Distributor\ShortFinancialResource;
use App\Http\Resources\Api\v1\User\ProfileResource;
use App\Http\Resources\OrderLongResource;
use App\Http\Resources\ProductResource;
use App\Models\Country;
use App\Models\Distributor;
use App\Models\DistributorProducts;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\DistributorCancelOrderNotification;
use App\Notifications\DistributorCompletedOrderNotification;
use App\Notifications\DistributorOnWayOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class DistributorController extends Controller
{
    private $country = null;

    public function __construct()
    {
        $request = request();
        $this->country = Country::find($request->header('country-id'));
    }

    public function receive_orders(Request $request)
    {
        $user = apiUser();
        $distributor = optional($user)->distributor;
        if (isset($distributor)) $distributor->update(['receive_orders' => !$distributor->receive_orders]);

        return apiSuccess(new ProfileResource($user));
    }

    public function get_products(Request $request)
    {
        $category_id = request()->get('category_id', false);
        if (isset($request->product_id)) return new ProductResource(Product::findOrFail($request->product_id));
        $products = DistributorProducts::with(['price', 'price.product', 'price.product.category'])->when($category_id, function ($query) use ($category_id) {
            return $query->whereHas('price', function ($q) use ($category_id) {
                return $q->whereHas('product', function ($qq) use ($category_id) {
                    return $qq->where('category_id', $category_id);

                });
            });
        })->where('user_id', apiUser()->id)->paginate(5050);;
//        $products = $products->pluck('price');
        return apiSuccess([
            'items' => DistributorProductsResource::collection($products),
            'paginate' => paginate($products),
        ]);;
    }

    private function getRandomDistributor()
    {
        $distributor = User::where('user_type', User::DISTRIBUTOR)->inRandomOrder()->first();
        $orders = optional(optional($distributor)->orders())->count();
        $counter = 1;
        while (!is_null($orders) && $orders != 0) {
            if ($counter > 10) return null; //loop just 10 times
            $counter++;
            $this->getRandomDistributor();
        }

        return $distributor;
    }

    public function change_order_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:' . Order::ON_WAY . ',' . Order::COMPLETED . ',' . Order::CANCELED
        ]);
        $order = Order::whereHas('distributor')->where('distributor_id', apiUser()->id)->findOrFail($id);
        if ($request->status == Order::CANCELED) {
            if ($order->status != Order::PENDING) return apiError(api('The status of Order does not allow it to be Canceled'));

            $distributor = $this->getRandomDistributor();// User::where('user_type', User::DISTRIBUTOR)->where('id', '<>', apiUser()->id)->first();
            $order->update(['distributor_id' => optional($distributor)->id]);
            Notification::send($order->user, new DistributorCancelOrderNotification($order));
        } else {
            if ($request->status == Order::ON_WAY) {
                if ($order->status != Order::PENDING) return apiError(api('The status of Order does not allow it to be On Way'));
                $order->update(['status' => Order::ON_WAY]);
                Notification::send($order->user, new DistributorOnWayOrderNotification($order));
            } else if ($request->status == Order::COMPLETED) {
                if ($order->status != Order::ON_WAY) return apiError(api('The status of Order does not allow it to be Completed'));
                $order->update(['status' => Order::COMPLETED]);
                $distributor = optional($order)->distributor;
                $distributor_data = optional($distributor)->distributor;
                $profit_percentage = optional($distributor_data)->profit_percentage;
                optional($distributor_data)->update([
                    'total_collected' => ($distributor_data->total_collected + $order->total_cost),
                    'total_profit' => ($distributor_data->total_profit + ($order->total_cost * ($profit_percentage / 100))),
                ]);
                $user = $order->user;
                $order->saveShareApp();
                $user->points = $user->points + optional(optional($user)->country)->getPoints($user);
                $user->save();
                $order->re_calculate_products_quantities_and_distributor_quantities();


                $order->update([
                    'distributor_profit_cost' => $order->total_cost * ($profit_percentage / 100)
                ]);
                $user->recalculatePoints();
                Notification::send($order->user, new DistributorCompletedOrderNotification($order));

                //decrease user points if there is gifts with that order
                if ($order->order_gifts()->count() > 0) {
                    $total_points = 0;
                    foreach ($order->order_gifts as $index => $order_gift) $total_points = $total_points + ($order_gift->points * $order_gift->quantity);
                    $user->update([
                        'points' => $user->points - $total_points
                    ]);
                }


            } else return apiError('error');
            $order->update([
                'status' => $request->status
            ]);
        }
        return apiSuccess(new OrderResource($order), api('Order Status Changed Successfully'));
    }

    public function home(Request $request)
    {
        $user = apiUser();
        $dist = optional($user)->distributor;
        return apiSuccess([
            'total' => optional($dist)->total_earned,
            'collected' => optional($dist)->total_collected,
            'profit' => optional($dist)->total_profit,
        ]);
    }

    public function orders(Request $request)
    {
        $order_id = request()->get('order_id', false);
        $status = request()->get('status', false);
        $query = Order::whereHas('distributor')->where('distributor_id', apiUser()->id)
            ->when($status, function ($query) use ($status) {
                if ($status == Order::PENDING_ON_WAY) $query->pendingOrOnWayOrder();
                else $query->where('status', $status);
            });
        $request['except_arr_resource'] = ['distributor'];
        if ($order_id) return apiSuccess(new OrderResource($query->findOrFail($order_id)));
        $request['except_arr_resource'] = [/*'rate',*/
            'distributor', 'order_products'];
        $orders = $query->paginate($this->perPage);
//        return apiSuccess(OrderResource::collection($orders));
        return apiSuccess([
            'items' => OrderResource::collection($orders->items()),
            'paginate' => paginate($orders),
        ]);
    }

    public function financial(Request $request)
    {
        $user = apiUser();
        $orders = $user->distributor_orders()->paginate($this->perPage);
        return apiSuccess([
            'earned_today' => $user->earned_today,
            'total_earned' => $user->total_earned,
            'history_items' => ShortFinancialResource::collection($orders),
            'paginate' => paginate($orders),
        ]);
//        return apiSuccess(new FinancialResource($orders));
        dd(checkRequestIsWorkingOrNot());
    }
}
