<?php

namespace App\Http\Controllers\Manager;

use App\Events\AcceptOrderEvent;
use App\Events\CancelOrderEvent;
use App\Events\OnProgressOrderEvent;
use App\Events\ReadyOrderEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\ChangeOrderDistributorNotification;
use App\Notifications\NewOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    private $_model;

    public function __construct(Order $order)
    {
        parent::__construct();
        $this->_model = $order;
//        $this->middleware('permission:Orders', ['only' => ['show', 'changeStatus', 'destroy']]);

        $this->validationRules["distributor_id"] = 'required|exists:users,id';
    }

    public function index(Request $request)
    {
        $title = t('Show Orders');
        $uuid = $request->get('uuid', false);
        $username = $request->get('username', false);
        $user_mobile = $request->get('user_mobile', false);
        $distributor = $request->get('distributor', false);
        $user = $request->get('user', false);
        $status = $request->get('status', false);
        $date_start = $request->get('date_start', false);
        $date_end = $request->get('date_end', false);
        $price_start = $request->get('price_start', false);
        $price_end = $request->get('price_end', false);
//        dd($username,$user_mobile,$distributor,$user,$status,$date_start,$date_end,$price_start,$price_end);
        $orders = $this->_model->when($uuid, function ($query) use ($uuid) {
            $query->where('id', $uuid);
        })
            ->when($username, function ($query) use ($username) {
                $query->whereHas('user', function ($query) use ($username) {
                    $query->searchName($username);
                });
            })
            ->whereHas('user', function ($query) {
                $query->where('country_id', getCurrentCountry()->id);
            })
            ->when($user_mobile, function ($query) use ($user_mobile) {
                $query->whereHas('user', function ($query) use ($user_mobile) {
                    $query->where('mobile', 'like', '%' . $user_mobile . '%');
                });
            })
            ->when($user, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($distributor, function ($query) use ($distributor) {
                $query->where('distributor_id', $distributor);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($date_start, function ($query) use ($date_start) {
                $query->whereDate('created_at', '>=', Carbon::parse($date_start));
            })
            ->when($date_end, function ($query) use ($date_end) {
                $query->whereDate('created_at', '<=', Carbon::parse($date_end));
            })
            ->when($price_start, function ($query) use ($price_start) {
                $query->where('total_cost', '>=', $price_start);
            })
            ->when($price_end, function ($query) use ($price_end) {
                $query->where('total_cost', '<=', $price_end);
            });
        if (request()->ajax()) {
//            dd(checkRequestIsWorkingOrNot());
            return DataTables::make($orders)
                ->escapeColumns([])
                ->addColumn('id', function ($order) {
                    return $order->id;
                })
                ->addColumn('user', function ($order) {
                    return optional($order->user)->name;
                })
                ->addColumn('mobile', function ($order) {
                    return optional($order->user)->mobile;
                })
                ->addColumn('distributor', function ($order) {
                    return optional($order->distributor)->name;
                })
                ->addColumn('status_name', function ($order) {
                    return $order->status_name . ' ' . ($order->user_cancel ? t(' User Cancel') : '');
                })
                ->addColumn('total', function ($order) {
                    return $order->total_cost;
                })
                ->addColumn('created_at', function ($order) {
                    return Carbon::parse($order->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($order) {
                    return $order->action_buttons;
                })
                ->addColumn('show_action', function ($order) {
                    return '<a href="' . route('manager.order.show', $order->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
                })
                ->make();
        }
        $total = $orders->sum('total_cost');
        return view('manager.order.index', compact('title', 'total'));
    }

    public function show($id)
    {
        $title = t('Show Order') . ' #' . $id;
        $order = Order::query()->findOrFail($id);
        return view('manager.order.show', compact('order', 'title'));
    }

    public function edit($id)
    {
        $title = t('Edit Order');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $order = $this->_model->findOrFail($id);
        $distributors = User::distributorUserType()->whereHas('country', function ($query) {
            $query->where('country_id', getCurrentCountry()->id);
        })->get();
        return view('manager.order.edit', compact('title', 'order', 'validator', 'distributors'));
    }

    public function store(Request $request)
    {
        $store = $this->_model->findOrFail($request->order_id);
        $request->validate($this->validationRules);
        $distributor_old = $store->distributor;
        $store->distributor_id = $request->distributor_id;
        $order = $store;
        $user = $order->user;
        if ($store->status != Order::COMPLETED && $request->status == Order::COMPLETED) $order->re_calculate_products_quantities_and_distributor_quantities();
        $store->status = $request->status;
        $store->save();
        if (!isset($distributor_old) || $store->distributor_id != $distributor_old->id) {
            $distributor_new = User::findOrFail($request->distributor_id);
            $request->lat = optional($order)->lat;
            $request->lng = optional($order)->lng;
            $country = optional($distributor_new)->country;
            $res = getShippingCostTime($request, $distributor_new, $country, $store);
//            dd($res);
            $old_shipping_cost = $order->shipping_cost;
            $order->update([
                'distance' => $res['distance'],
                'delivery_time' => $res['delivery_time'],
                'shipping_cost' => $res['delivery_cost'],
                'total_cost' => $order->total_cost - $old_shipping_cost + $res['delivery_cost'],
            ]);
            \Illuminate\Support\Facades\Notification::send($distributor_new, new NewOrderNotification($store));
            if (isset($distributor_old)) \Illuminate\Support\Facades\Notification::send($distributor_old, new ChangeOrderDistributorNotification($store));
        }
        if ($request->status == Order::COMPLETED) {
//            هنا بدي اعطيه نقاط اذا كان مسجل في التطبيق جديد وعمل اول اوردر مكتمل
            $user->recalculatePoints();
//هنا بدي اعطي المستخدم الي عمل شير للتطبيق ومن ثم صاحب رقم الجوال الي انعمل له الشير اذا عمل طلب مكتمل فانا هعطي المستخدم الي عمل الشير النقاط
            $order->saveShareApp();

            //decrease user points if there is gifts with that order
            if ($order->order_gifts()->count() > 0) {
                $total_points = 0;
                foreach ($order->order_gifts as $index => $order_gift) $total_points = $total_points + ($order_gift->points * $order_gift->quantity);
                $user->update([
                    'points' => $user->points - $total_points
                ]);
            }

        }

        $message = isset($request->distributor_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route('manager.order.index')->with('m-class', 'success')->with('message', $message);
    }


    public function destroy($id)
    {
        $order = Order::query()->findOrFail($id);
        $order->delete();
        return redirect()->route('manager.order.index')->with('message', t('Successfully Deleted'))->with('m-class', 'success');
    }
}
