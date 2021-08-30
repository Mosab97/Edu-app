<?php

namespace App\Http\Controllers\Api\v1\notifications;

use App\Http\Controllers\Api\v1\Controller;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationsController extends Controller
{

    public function sendNotificationForAllUsers(Request $request)
    {
//        send_to_topic('users', [
//            'title' => $request->get('title'),
//            'body' => $request->get('body')
//        ]);
//        return apiSuccess('done');

//        Notification::send(User::get(), new GeneralNotification($request->get('title'), $request->get('body')));
        $user = User::findOrFail(57);
        $title = [
            'ar' => $request->title,
            'en' => $request->title,
        ];
        $body = [
            'ar' => $request->body,
            'en' => $request->body,
        ];
        Notification::send($user, new GeneralNotification($title, $body));
        return apiSuccess('done');
    }
}
