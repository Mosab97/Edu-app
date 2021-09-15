<?php

namespace App\Providers;

use App\Models\Country;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


//        if (Schema::hasTable('settings')) {
//
//            App::singleton('settings', function () {
//                return Setting::getSettings();
//            });
//
//
//            View::share('settings', app('settings'));
//        }


    }
}
