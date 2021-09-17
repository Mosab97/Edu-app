<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Advantage;
use App\Models\Blog;
use App\Models\ContactUs;
use App\Models\CustomerReviews;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Service;
use App\Models\Statistic;
use App\Models\User;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:General Settings', ['only' => ['settings', 'updateSettings']]);
    }

    public function home()
    {
        $users =0;// User::count();
        $packages =0;// Package::count();
        $services = 0;//Service::count();
        $blogs = 0;//Blog::count();
        $advantages =0;// Advantage::count();
        $statistics = 0;//Statistic::count();
        $customerReviews = 0;//CustomerReviews::count();
        $fAQs = 0;//Faq::count();
        $contactUsMessages = 0;//ContactUs::count();


        return view('manager.home', compact('users', 'packages', 'services', 'blogs', 'advantages', 'statistics', 'customerReviews', 'fAQs', 'contactUsMessages'));
    }

    public function settings()
    {
        $title = t('Show Settings');
        return view('manager.setting.general', compact('title'));
    }

    public function updateSettings(Request $request)
    {
    //    dd(checkRequestIsWorkingOrNot());
        $data = $request->except(['_token', 'commission_active', 'logo', 'logo_min', 'showcase_background', 'showcase_background_front', 'brochure', 'logo_light', 'about_us_image']);
        setting(['commission_active' => $request->get('commission_active', false)])->save();
        if ($request->hasFile('logo_light')) if ($request->file('logo_light')->isValid()) setting(['logo_light' => $this->uploadImage($request->file('logo_light'), 'logo_light')])->save();
        if ($request->hasFile('about_us_image')) if ($request->file('about_us_image')->isValid()) setting(['about_us_image' => $this->uploadImage($request->file('about_us_image'), 'about_us_image')])->save();
        if ($request->hasFile('logo')) if ($request->file('logo')->isValid()) {
            $logo = $this->uploadImage($request->file('logo'), 'logos');
            setting(['logo' => $logo])->save();
            setting(['logo_min' => $logo])->save();
        }
        if (isset($request->showcase_background) && is_array($request->showcase_background) && sizeof($request->showcase_background) > 0) {
            $imgArr = [
                'ar' => optional(setting('showcase_background'))['ar'],
                'en' => optional(setting('showcase_background'))['en'],
            ];
            foreach ($request->showcase_background as $index => $item)
                if ($item->isValid()) $imgArr[$index] = $this->uploadImage($item, 'showcase_background_' . $index);
//                dd($imgArr);
            setting(['showcase_background' => $imgArr])->save();
        }
//        if ($request->hasFile('showcase_background')) if ($request->file('showcase_background')->isValid()) setting(['showcase_background' => $this->uploadImage($request->file('showcase_background'), 'showcase_background')])->save();
        if ($request->hasFile('showcase_background_front'))
            if ($request->file('showcase_background_front')->isValid())
                setting(['showcase_background_front' =>
                    $this->uploadImage($request->file('showcase_background_front'), 'showcase_background_front')])->save();

        if ($request->hasFile('brochure'))
            if ($request->file('brochure')->isValid())
                setting(['brochure' =>
                    $this->uploadImage($request->file('brochure'), 'brochure')])->save();
//        if (isset($request->showcase_background) && is_array($request->showcase_background) && sizeof($request->showcase_background) > 0) {
//            $imgArr = [
//                'ar' => optional(setting('showcase_background'))['ar'],
//                'en' => optional(setting('showcase_background'))['en'],
//            ];
//            foreach ($request->showcase_background as $index => $item)
//                if ($item->isValid()) $imgArr[$index] = $this->uploadImage($item, 'showcase_background_' . $index);
////                dd($imgArr);
//            setting(['showcase_background' => $imgArr])->save();
//        }

        foreach ($data as $index => $datum) {
            if (is_array($datum) && sizeof($datum) > 0) foreach ($datum as $index2 => $item) if (is_null($item)) $datum[$index2] = '';
            if (is_null($datum)) $datum = '';
            setting([$index => $datum])->save();
        }
        Artisan::call('cache:clear');
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function lang($local)
    {
        session(['lang' => $local]);
        if (Auth::guard('manager')->check()) {
            $user = Auth::guard('manager')->user();
            $user->update([
                'local' => $local,
            ]);
        }
        app()->setLocale($local);
        return back();
    }

    public function view_profile()
    {
        $title = t('Show Profile');
        return view('manager.setting.profile', compact('title'));
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('manager')->user();
        $this->validationRules['password'] = 'nullable';
        $this->validationRules['email'] = ['required', 'unique:managers,email,' . $user->id, new EmailRule()];
        $request->validate($this->validationRules);

        $data = $request->all();
        if ($request->has(['password', 'password_confirmation']) && !empty($request->get('password'))) {
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $user->password;
        }
        $user->update($data);
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }
}
