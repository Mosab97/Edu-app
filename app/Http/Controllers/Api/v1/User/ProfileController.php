<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\General\ProfileResource;
use App\Models\File;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileController extends Controller
{

    public function profile()
    {
        return apiSuccess(new ProfileResource(apiUser()));
    }

    public function updateProfile(Request $request)
    {
        $user = apiUser();
        $request->validate([
            'name' => 'required|min:3|max:100',
            'phone' => ['required', 'numeric', 'unique:users,phone,' . $user->id . ',id,deleted_at,NULL'],
            'image' => 'sometimes|image',
            'demonstration_video' => 'sometimes|mimes:mp4,mov,ogg,qt | max:20000',

        ]);
        $data = $request->except(['image', 'demonstration_video', 'major', 'experience']);
        if (isset($request->image) && $request->hasFile('image')) {
            $image_path = $this->uploadImage($request->file('image'), 'users');
            if (File::where(['target_id' => $user->id, 'target_type' => User::class])->count() > 0) {
                File::where(['target_id' => $user->id, 'target_type' => User::class])->update(['path' => optional($image_path)['path'], 'name' => optional($image_path)['name'], 'extension' => optional($image_path)['extension'], 'target_type' => User::class]);
            } else {
                File::create(['path' => optional($image_path)['path'], 'name' => optional($image_path)['name'], 'extension' => optional($image_path)['extension'], 'target_id' => $user->id, 'target_type' => User::class]);
            }
        }
        $user->update($data);
        if ($user->user_type == User::user_type['TEACHER']) {
            $data = [];

            $data['major'] = $request->major;
            $data['experience'] = $request->experience;
            $teacher_details = $user->teacher_details;
            if (isset($teacher_details)) $teacher_details->update($data);
            else $user->teacher_details()->create($data);

            if (isset($teacher_details) && isset($request->demonstration_video) && $request->hasFile('demonstration_video')) {
                $file_path = $this->uploadImage($request->file('demonstration_video'), 'teachers');
                if (File::where(['target_id' => $teacher_details->id, 'target_type' => Teacher::class])->count() > 0) {
                    File::where(['target_id' => $teacher_details->id, 'target_type' => Teacher::class])->update(['path' => optional($file_path)['path'], 'name' => optional($file_path)['name'], 'extension' => optional($file_path)['extension'], 'target_type' => Teacher::class]);
                } else {
                    File::create(['path' => optional($file_path)['path'], 'name' => optional($file_path)['name'], 'extension' => optional($file_path)['extension'], 'target_id' => $teacher_details->id, 'target_type' => Teacher::class]);
                }
            }

        }
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
