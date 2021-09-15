<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FinancialSystemController extends Controller
{
    private $_model;

    public function __construct(Payment $payment)
    {
        $this->_model = $payment;
        parent::__construct();
    }

    public function index(Request $request)
    {
        $title = t('Financial System');
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
        $payments = $this->_model->when($uuid, function ($query) use ($uuid) {
            $query->where('id', $uuid);
        })
            ->whereHas('user', function ($query) {
                $query->where('country_id', getCurrentCountry()->id);
            })
            ->when($username, function ($query) use ($username) {
                $query->whereHas('user', function ($query) use ($username) {
                    $query->searchName($username);
                });
            })
            ->when($user_mobile, function ($query) use ($user_mobile) {
                $query->whereHas('user', function ($query) use ($user_mobile) {
                    $query->where('mobile', 'like', '%' . $user_mobile . '%');
                });
            })
            ->when($user, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($status, function ($query) use ($status) {
                $query->whereHas('order', function ($query) use ($status) {
                    $query->where('status', $status);
                });
            })
            ->when($date_start, function ($query) use ($date_start) {
                $query->whereDate('created_at', '>=', Carbon::parse($date_start));
            })
            ->when($date_end, function ($query) use ($date_end) {
                $query->whereDate('created_at', '<=', Carbon::parse($date_end));
            })
            ->when($price_start, function ($query) use ($price_start) {
                $query->where('amount', '>=', $price_start);
            })
            ->when($price_end, function ($query) use ($price_end) {
                $query->where('amount', '<=', $price_end);
            });
        if (request()->ajax()) {
//            dd(checkRequestIsWorkingOrNot());
            return DataTables::make($payments)
                ->escapeColumns([])
                ->addColumn('id', function ($payment) {
                    return $payment->id;
                })
                ->addColumn('user', function ($payment) {
                    return optional($payment->user)->name;
                })
                ->addColumn('order_id', function ($payment) {
                    return '<a href="' . route('manager.order.show', $payment->id) . '" >#' . $payment->id . '</>';
                })
                ->addColumn('total', function ($payment) {
                    $user = $payment->user;
                    return ($user->user_type == User::CUSTOMER) ? $payment->amount : ($payment->amount * -1);
                })
                ->addColumn('payment_type', function ($payment) {
                    $bColor = (optional(optional($payment)->order)->paid_type == Order::CASH) ? '#CAA12E' : '#2E7CCA';
                    return '<button class="btn " style="background-color: ' . $bColor . ' !important;color: #fff">' . optional(optional($payment)->order)->paid_name . '</button>';
                    return '<span style="color: #CAA12E">' . $payment->paid_name . '</span>';

                })
                ->addColumn('created_at', function ($payment) {
                    return Carbon::parse($payment->created_at)->toDateTimeString();
                })
//                ->addColumn('actions', function ($payment) {
//                    return $payment->action_buttons;
//                })
                ->make();
        }
        return view('manager.financial_system.index', compact('title'));
    }

}
