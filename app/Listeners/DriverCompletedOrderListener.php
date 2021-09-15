<?php

namespace App\Listeners;

use App\Events\AcceptOrderEvent;
use App\Events\CancelOrderEvent;
use App\Events\DriverAcceptOrderEvent;
use App\Events\DriverCompletedOrderEvent;
use App\Events\DriverOnWayOrderEvent;
use App\Models\Branch;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AcceptOrderNotification;
use App\Notifications\DriverAcceptOrderNotification;
use App\Notifications\DriverCompletedOrderNotification;
use App\Notifications\DriverOnWayOrderNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class DriverCompletedOrderListener
{
    public function handle(DriverCompletedOrderEvent $event)
    {
        $order = $event->order;
        if ($order instanceof Order) {
            $branch = Branch::query()->where('id', $order->branch_id)->first();
            $user = User::query()->where('id', $order->user_id)->first();
            if ($branch) Notification::send($branch, new DriverCompletedOrderNotification($order));
            if ($user) Notification::send($user, new DriverCompletedOrderNotification($order));
            $order->update([
                'status' => Order::COMPLETED,
                'status_time_line' => getNewEncodedArray(getAnonymousStatusObj(Order::COMPLETED, 'COMPLETED'), $order->status_time_line),
            ]);

        }
    }
}
