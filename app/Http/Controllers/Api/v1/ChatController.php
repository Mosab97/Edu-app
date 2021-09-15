<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\Api\v1\User\ShortProfileResource;
use App\Http\Resources\ChatMessageResource;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Country;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ChatController extends Controller
{
    private $country = null;

    public function __construct()
    {
        parent::__construct();
        $request = request();
        $this->country = Country::find($request->header('country-id'));
    }

    public function getAllChats(Request $request)
    {
        $user = apiUser();
        $request['user_type'] = $user->user_type;
        if ($user->user_type == User::CUSTOMER || $user->user_type == User::MERCHANT) {
            $chats = Order::withoutGlobalScope('orderedBy')->with(['distributor'])->hasChat()->where('user_id', $user->id)->paginate($this->perPage);
        } else if ($user->user_type == User::DISTRIBUTOR) {
            $chats = Order::withoutGlobalScope('orderedBy')->with(['user'])->hasChat()->where('distributor_id', $user->id)->paginate($this->perPage);
//            $chats = $user->distributor_orders->pluck('user');
        } else $chats = [];
        return apiSuccess([
            'items' => ShortProfileResource::collection($chats->items()),// ChatMessageResource::collection($messages->items()),
            'paginate' => paginate($chats),
        ]);
    }

    public function chatMessages(Request $request, $id)
    {
        $messages = ChatMessage::where('order_id', $id)->paginate($this->perPage);
        return apiSuccess([
            'items' => ChatMessageResource::collection($messages->items()),
            'paginate' => paginate($messages),
        ]);
    }

    public function createNewChat(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);
        $order = Order::findOrFail($request->order_id);
        if ($order->has_chat) return apiError(api('Chat Already Created'));
        $order->update(['has_chat' => true]);
        app('firebase.firestore')->database()->collection('chat')->document($order->id)->set([]);
        return apiSuccess(null, api('Chat Saved Successfully'));
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
//            'order_id' => 'required|exists:orders,id',
            'message' => 'required|min:1|max:200',
            'image' => 'sometimes|image',
        ]);
        $order = Order::findOrFail($id);
        $image = $request->hasFile('image') ? $this->uploadImage($request->file('image'), 'chat') : null;
        $chatMessage = $order->chatMessages()->create([
            'sender_id' => apiUser()->id,
            'message' => $request->message,
            'image' => $image,
        ]);
        $user = apiUser();
        if ($user->id == $order->user_id) {
            $user = $order->distributor;
            if (!isset($user)) return apiError('You are not allowed to send message on this order because there is no distributor for this order');
        } else if ($user->id == $order->distributor_id) {
            $user = $order->user;
        } else {
            return apiError('You are not allowed to send message on this order');
        }
        Notification::send($user, new NewMessageNotification($order, $chatMessage));
        app('firebase.firestore')->database()->collection('chat')->document($order->id)
            ->set([
                    'sender_id' => apiUser()->id,
                    'message' => $request->message,
                    'image' => $chatMessage->image,
                    'timestamp' => Carbon::parse($request->updated_at)->format(DATE_FORMAT_FULL),
                ]
            );

        return apiSuccess(null, api('Message Send Successfully'));
    }

}
