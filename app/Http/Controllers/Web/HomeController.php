<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Advantage;
use App\Models\Blog;
use App\Models\ContactUs;
use App\Models\CustomerReviews;
use App\Models\Faq;
use App\Models\Manager;
use App\Models\Package;
use App\Models\Service;
use App\Models\Statistic;
use App\Models\User;
use App\Notifications\ContactUsNotification;
use App\Rules\EmailRule;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class HomeController extends Controller
{
    private $view = 'website.';

    public function welcome()
    {
        $title = t('Home');
        $top_services = Service::limit(6)->get();
        $packages = Package::with(['values', 'values.package'])->limit(3)->get();
        $customerReviews = CustomerReviews::limit(9)->get();
        $statistics = Statistic::limit(4)->get();
        $advantages = Advantage::limit(6)->get();
        $faq = Faq::limit(6)->get();
        $faq = [
            $faq->take(3),
            $faq->skip(3)->take(3)
        ];
//        $faq_1st = $faq->take(3);
//        $faq_2sd = $faq->skip(3)->take(3);
        return view($this->view . 'welcome', compact('top_services',
            'packages', 'faq', 'customerReviews', 'statistics', 'title', 'advantages'));
    }

    public function privacy_policy(Request $request)
    {
        return view($this->view . 'privacy_policy');
    }

    public function faq(Request $request)
    {
        return view($this->view . 'faq');
    }

    public function conditions(Request $request)
    {
        return view($this->view . 'conditions');
    }

    public function blogs(Request $request)
    {
        $blogs = Blog::paginate(6);
        return view($this->view . 'blogs', compact('blogs'));
    }

    public function blog(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        return view($this->view . 'blog', compact('blog'));
    }

    public function view_contactUs(Request $request)
    {
        $contValidationRules["name"] = 'required|max:255';
        $contValidationRules["email"] = 'required|email';
        $contValidationRules["mobile"] = 'required|max:13|min:13';
        $contValidationRules["target"] = 'required';
        $contValidationRules["how_did_you_hear_about_ingaz"] = 'required';
        $contValidationRules["message"] = 'required';
        $cont_validator = JsValidator::make($contValidationRules, $this->validationMessages);
        return view($this->view . 'contact_us', compact('cont_validator'));
    }

    public function contactUs(Request $request)
    {
        $contValidationRules["name"] = 'required|max:255';
        $contValidationRules["email"] = 'required|email';
        $contValidationRules["mobile"] = 'required|max:13|min:13';
        $contValidationRules["target"] = 'required';
        $contValidationRules["how_did_you_hear_about_ingaz"] = 'required';
        $contValidationRules["message"] = 'required';
        $request->validate($contValidationRules);
        $data = $request->all();
        $contact = ContactUs::create($data);
        Notification::send(Manager::query()->get(), new ContactUsNotification($contact));
        return redirect()->back()->with('message', w('Message Sent Successfully'))->with('m-class', 'success');
    }

    public function about_us(Request $request)
    {
        $statistics = Statistic::limit(4)->get();
        return view($this->view . 'about_us', compact('statistics'));
    }

    public function all_faq(Request $request)
    {
        $faq = Faq::get();
        return view($this->view . 'all_faq', compact('faq'));
    }

    public function profile(Request $request)
    {
        $user = user('web');

        $contValidationRules["name"] = 'required|max:255';
        $contValidationRules["username"] = ['required', 'unique:users,username,' . $user->id . ',id,deleted_at,NULL'];
        $contValidationRules["email"] = ['required', new EmailRule(), 'unique:users,email,' . $user->id . ',id,deleted_at,NULL'];
        $contValidationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,' . $user->id . ',id,deleted_at,NULL'];
        $contValidationRules["country"] = 'required|max:100';
        $contValidationRules["city"] = 'required|max:100';
        $contValidationRules["client_type"] = 'required|in:' . implode(',', User::client_type);
        $validator = JsValidator::make($contValidationRules, $this->validationMessages);
        return view($this->view . 'profile', compact('validator', 'user'));
    }

    public function post_profile(Request $request)
    {
        $user = user('web');
        $contValidationRules["name"] = 'required|max:255';
        $this->validationRules["username"] = ['required', 'unique:users,username,' . $user->id . ',id,deleted_at,NULL'];
        $this->validationRules["email"] = ['required', new EmailRule(), 'unique:users,email,' . $user->id . ',id,deleted_at,NULL'];
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,' . $user->id . ',id,deleted_at,NULL'];
        $contValidationRules["country"] = 'required|max:100';
        $contValidationRules["city"] = 'required|max:100';
        $contValidationRules["client_type"] = 'required|in:' . implode(',', User::client_type);
        $request->validate($contValidationRules);
        $user->name = [
            'ar' => $request->name,
            'en' => $request->name,
        ];
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->client_type = $request->client_type;
        $user->username = $request->username;
        if (isset($request->password)) $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->back()->with('message', w('Profile Updated Successfully'))->with('m-class', 'success');

    }

}
