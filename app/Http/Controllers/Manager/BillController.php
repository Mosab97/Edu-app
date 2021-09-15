<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductPrices;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillController extends Controller
{
    private $_model;

    public function __construct(Order $order)
    {
        parent::__construct();
        $this->_model = $order;
    }

    public function mark_all_as_paid(Request $request, $distributor_id, $date = null)
    {
        $distributor = User::distributorUserType()->findOrFail($distributor_id);
        $orders = $distributor->distributor_orders()->with(['order_products', 'distributor', 'user'])->completedOrder()->get();
        foreach ($orders as $index => $order) {
            $order->update(['distributor_take_his_profit' => true]);
            if ($order->payments()->count() == 0)
                $payment = $order->payments()->create([
                    'user_id' => $distributor_id,
                    'amount' => $order->total_cost,
                    'date' => $date,
                ]);
        }
        return redirect()->back();
    }

    public function mark_as_paid(Request $request, $distributor_id, $date = null)
    {
        $date = $request->get('date', Carbon::now()->format(DATE_FORMAT));
        $date = Carbon::parse($date)->format(DATE_FORMAT);
        $distributor = User::distributorUserType()->findOrFail($distributor_id);
        $orders = $distributor->distributor_orders()->with(['order_products', 'distributor', 'user'])->completedOrder()->whereDate('created_at', $date)->get();
        foreach ($orders as $index => $order) {
            $order->update(['distributor_take_his_profit' => true]);
            if ($order->payments()->count() == 0)
                $payment = $order->payments()->create([
                    'user_id' => $distributor_id,
                    'amount' => $order->total_cost,
                    'date' => $date,
                ]);
        }
        return redirect()->back();
    }

    public function bills(Request $request, $distributor_id)
    {
        $date = $request->get('date', Carbon::now()->format(DATE_FORMAT));
        $date = Carbon::parse($date)->format(DATE_FORMAT);
        $distributor = User::distributorUserType()->findOrFail($distributor_id);
        $orders = $distributor->distributor_orders()->with(['order_products', 'distributor', 'user'])
            ->withCount('order_products')->completedOrder()->whereDate('created_at', $date)->get();
//        if ($date) $orders = $orders->whereDate('created_at', $date);
//        else $orders = $orders->whereDay('created_at', date('d'));
        $data = [
            'sub_total' => $orders->sum('products_cost'),
            'shipping' => $orders->sum('shipping_cost'),
            'vat' => $orders->sum('vat_cost'),
            'total' => $orders->sum('total_cost'),
            'distributor_total_profit_cost_this_date' => $orders->where('distributor_take_his_profit', false)->sum('distributor_profit_cost'),
            'distributor_total_profit_cost' => $distributor->distributor_orders()->completedOrder()->where('distributor_take_his_profit', false)->sum('distributor_profit_cost'),
            'status' => Payment::where('date', $date)->count() > 0 ? t('Paid') : t('Not Paid'),
            'date' => $date,
        ];
        return view('manager.bills.show', compact('distributor', 'data', 'orders'));
    }

}
