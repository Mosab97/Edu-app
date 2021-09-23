<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\General\AgeResource;
use App\Http\Resources\Api\v1\Teacher\GroupStudentResource;
use App\Models\Age;
use App\Models\ContactUs;
use App\Models\Group;
use App\Models\Manager;
use App\Models\User;
use App\Notifications\ContactUsNotification;
use App\Rules\EmailRule;
use App\Rules\StartWith;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    public function group_students(Request $request, $group_id)
    {
        $group = Group::findOrFail($group_id);
        $students = $group->students->pluck('student')->map(function ($item) use ($group) {
            $item['group_name'] = $group->name;
            return $item;
        });
        return apiSuccess(GroupStudentResource::collection($students));
    }

    public function settings()
    {
        return apiSuccess([
            'test' => 'sdfdsf'
        ]);
    }

    public function ages()
    {
        return apiSuccess(AgeResource::collection(Age::get()));
    }

    public function contactUs(Request $request)
    {
        $request->validate([
            'name' => 'required|max:250',
            'title' => 'required|min:3|max:100',
            'mobile' => ['required', new StartWith('+')],
            'message' => 'required|min:3|max:200',
            'email' => ['nullable', 'email', 'max:50', new EmailRule()],
        ]);

        $user = apiUser();
        $contact = ContactUs::create([
            'name' => $request->name,
            'title' => $request->title,
            'mobile' => $request->mobile,
            'message' => $request->message,
            'email' => $request->email,
            'user_id' => optional($user)->id,
        ]);
        Notification::send(Manager::query()->get(), new ContactUsNotification($contact));
        return apiSuccess(null, api('Message Sent Successfully'));
    }

}
