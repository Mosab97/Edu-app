<?php

namespace App\Http\Middleware;

use App\Models\Country;
use App\Models\Order;
use App\Models\User;
use Closure;

class CheckOrderOwner
{

//    public function handle($request, Closure $next)
//    {
//        $user = apiUser();
//        if ($user->type == User::CUSTOMER) {
//            $order = Order::findOrFail();
//        }
//        if ($user->type == User::DISTRIBUTOR) {
//
//        } else {
//
//        }
//        $country = ($request->hasHeader('country-id')) ? $request->header('country-id') : null;
//        if (optional(getSettings('stop_app_all_countries'))->value == true) {
//            return apiError(api('Our app is closed now'), STOP_MOBILE_APP);
//        } else if (isset($country)) {
//            $country = Country::findOrFail($country);
//            if ($country->app_is_stopped == true) return apiError(api('Our app is closed now'), STOP_MOBILE_APP);
//            else return $next($request);
//        } else {
//            return $next($request);
//
//        }
//    }
}
