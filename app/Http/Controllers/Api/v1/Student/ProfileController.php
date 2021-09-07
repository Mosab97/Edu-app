<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\User\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileController extends Controller
{

    public function profile()
    {
        $user = user('student');
        return apiSuccess(new ProfileResource($user));
    }

    public function updateProfile(Request $request)
    {
        $user = user('student');
        $request->validate([
            'name' => 'required|min:3|max:100',
            'phone' => ['required', 'numeric', 'unique:students,phone,' . $user->id . ',id,deleted_at,NULL'],
            'image' => 'sometimes|image',
        ]);
        $user->name = $request->name;
        $user->phone = $request->phone;
        if ($request->hasFile('image')) $user->image = $this->uploadImage($request->file('image'), 'students');
        $user->save();
//        $user['access_token'] = Str::substr(request()->header('Authorization'), 7);
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
