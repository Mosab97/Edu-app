<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\User\NotificationResource;
use App\Http\Resources\Api\v1\User\ProfileResource;
use App\Models\Notification;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
//        dd(\auth()->user());
    }

    public function notifications(Request $request)
    {
        $user = user('student');
        $notifications = Notification::where('notifiable_id', $user->id)
            ->where('created_at', '>=', $user->created_at)->paginate($this->perPage);
        return apiSuccess([
            'items' => NotificationResource::collection($notifications->items()),
            'paginate' => paginate($notifications),
            'unread_notifications' => $user->unread_notifications,
        ]);
    }

    public function notification($id)
    {

        $user = apiUser();
        $notification = Notification::query()
            ->where(function ($query) use ($user) {
                $query->where('notifiable_id', $user->id)->orWhere('notifiable_id', 0);
            })
            ->find($id);

        if (!$notification) {
            return apiError(api('Notification Not Found'));
        }
        if (!$notification->seen && $notification->notifiable_id != 0) {
            $notification->update([
                'read_at' => now(),
            ]);
        }
        return apiSuccess([
            'item' => new NotificationReasource($notification),
            'unread_notifications' => $user->unread_notifications,
        ]);
    }

    public function profile()
    {
        $user = apiUser();
//        $user['access_token'] = Str::substr(request()->header('Authorization'), 7);
        return $this->sendResponse(new ProfileResource($user));
    }

    public function updateProfile(Request $request)
    {
        $user = apiUser();
        $request->validate([
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'email' => ['sometimes', 'required', 'unique:users,email,' . $user->id . ',id', new EmailRule()],
//            'mobile' => ['required', 'numeric', 'unique:users,mobile,' . apiUser()->id . ',id,deleted_at,NULL'],
            'image' => 'sometimes|image',
        ]);

//        $old_mobile = $user->mobile;
//        $new_mobile = $request->get('mobile');
        $user->first_name = [
            'ar' => $request->first_name,
            'en' => $request->first_name,
        ];
        $user->last_name = [
            'ar' => $request->last_name,
            'en' => $request->last_name,
        ];
        $user->email = $request->email;
        if ($request->hasFile('image')) $user->image = $this->uploadImage($request->file('image'), 'users');
//        if ($old_mobile != $new_mobile) {
//            $SMS_code = generateCode();
//            $SMS_code = 1234;
//            $user->verified = false;
//            $user->generatedCode = $SMS_code;
//            $user->mobile = $request->mobile;
//        }
        $user->save();
        $user['access_token'] = Str::substr(request()->header('Authorization'), 7);
        return apiSuccess(new ProfileResource($user), api('Profile Updated Successfully'));
    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:ar,en'
        ]);
        $user = apiUser();
        $user->update(['local' => $request->get('language')]);
        $user['access_token'] = Str::substr(request()->header('Authorization'), 7);

        return apiSuccess(new ProfileResource($user));
    }

    public function update_location(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
        ]);
        $user = apiUser();
        $user->update([
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng')
        ]);
        return apiSuccess(null, api('Location Updated Successfully'));
    }

    public function updateNotification(Request $request)
    {
        $request->validate([
            'notification' => 'required|in:1,0',
        ]);
        $user = apiUser();
        $user->update(['notification' => $request->get('notification')]);
        return apiSuccess(new ProfileResource($user));
    }


}
