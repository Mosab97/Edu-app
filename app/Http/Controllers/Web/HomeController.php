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
        return redirect()->route('manager.home');

        $title = t('Home');
        $top_services = collect([]);//Service::limit(6)->get();
        $packages = collect([]);//Package::with(['values', 'values.package'])->limit(3)->get();
        $customerReviews = collect([]);//CustomerReviews::limit(9)->get();
        $statistics =collect([]);// Statistic::limit(4)->get();
        $advantages = collect([]);//Advantage::limit(6)->get();
        $faq =collect([]); //Faq::limit(6)->get();
        $faq = [
            $faq->take(3),
            $faq->skip(3)->take(3)
        ];
//        $faq_1st = $faq->take(3);
//        $faq_2sd = $faq->skip(3)->take(3);
        return view($this->view . 'welcome', compact('top_services',
            'packages', 'faq', 'customerReviews', 'statistics', 'title', 'advantages'));
    }


}
