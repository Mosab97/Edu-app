<?php

namespace App\Http\Controllers\Manager;

use App\Events\RestaurantNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ContactUs;
use App\Models\Merchant;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AcceptOrderNotification;
use App\Notifications\AddNewBalanceNotification;
use App\Notifications\CancelOrderNotification;
use App\Notifications\ContactUsNotification;
use App\Notifications\GeneralNotification;
use App\Notifications\NewOrderNotification;
use App\Notifications\RateOrderNotification;
use App\Notifications\ReadyOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:Notification', ['only' => ['show', 'store', 'destroy']]);
    }

    public function index()
    {
        $title = t('Show Notifications');
        if (request()->ajax()) {
            $notifications = Notification::query()->latest();

            return DataTables::make($notifications)
                ->escapeColumns([])
                ->addColumn('created_at', function ($notification) {
                    return Carbon::parse($notification->created_at)->toDayDateTimeString();
                })
                ->addColumn('title', function ($notification) {
                    return $notification->title;
                })
                ->addColumn('status', function ($notification) {
                    return is_null($notification->read_at) ? t('Unseen') : t('Seen');
                })
                ->addColumn('type', function ($notification) {
                    return $notification->type_name;
                })
                ->addColumn('actions', function ($notification) {
                    return $notification->action_buttons;
                })
                ->make();
        }
        return view('manager.notification.index', compact('title'));
    }

    public function show($id)
    {
        $title = t('Show Notification');
        $notification = Notification::query()->find($id);
        $notification->update([
            'read_at' => now(),
        ]);
        if (in_array($notification->type, [NewOrderNotification::class])) {
            $order = Order::query()->where('id', optional(optional(optional($notification)['data'])['others'])['order_id'])->first();
            return redirect()->route('manager.order.show', $order->id);
        }
        if (in_array($notification->type, [ContactUsNotification::class])) {
            $contact = ContactUs::query()->where('id', $notification['data']['others']['contact_id'])->first();
            return redirect()->route('manager.contact_us.show', $contact->id);
        }
        return view('manager.notification.show', compact('notification'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'recipients' => 'required',
        ]);
        $items = null;
        switch ($request->recipients) {
            case ALL_USERS:
                $items = User::get();
                break;
            case CUSTOMERS:
                $items = User::customer()->get();
                break;
            case MERCHANTS:
                $items = User::merchantUserType()->get();
                break;
            case DISTRIBUTORS:
                $items = User::distributorUserType()->get();
                break;
            default:
                $items = User::get();
                break;
        }
        $title = [
            'ar' => $request->title,
            'en' => $request->title,
        ];
        $body = [
            'ar' => $request->content,
            'en' => $request->content,
        ];
        \Illuminate\Support\Facades\Notification::send($items, new GeneralNotification($title, $body));
        return redirect()->back()->with('message', t('Notification Sent Successfully'))->with('m-class', 'success');

    }

    public function destroy($id)
    {
        $notification = Notification::query()->find($id);
        if ($notification->notifiable_id == 0) {
            return redirect()->back()->with('message', t('Can Not Delete General Notification'))->with('m-class', 'error');
        }
        $notification->delete();
        return redirect()->back()->with('message', t('Successfully Deleted'))->with('m-class', 'success');
    }
}
