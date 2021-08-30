<?php

namespace App\Providers;

use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if (Schema::hasTable('settings')) {
            App::singleton('settings', function () {
                return Setting::getSettings();
            });
            View::share('settings', app('settings'));
        }
        if (Schema::hasTable('services')) {
            App::singleton('services', function () {
//                dd(Service::first()->name);
                return Service::get();
            });
            View::share('services', app('services'));
        }

//        view()->share('notifications',\App\Models\ContactUs::where('seen',0)->latest()->get());
    }
}
