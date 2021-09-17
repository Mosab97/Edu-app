<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\Notification;
use App\Models\Order;
use App\Notifications\NewOrderNotification;

class NotificationController extends Controller
{
    public function show($id)
    {
        $title = t('Show Notification');
        $notification = Notification::query()->find($id);
        $notification->update([
            'read_at' => now(),
        ]);
        if (in_array($notification->type, [NewOrderNotification::class,])) {
            $order = Order::query()->where('id', $notification['data']['others']['order_id'])->first();
            return redirect()->route('manager.order.show', $order->id);
        }
        if (in_array($notification->type, [ContactUsNotification::class])) {
            $contact = ContactUs::query()->where('id', $notification['data']['others']['contact_id'])->first();
            return redirect()->route('manager.contact_us.show', $contact->id);
        }
        return view('manager.notification.show', compact('notification'));
    }
}
