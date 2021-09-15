<?php

namespace App\Http\Middleware;

use App\Models\Country;
use Closure;

class CheckAppIsStopped
{

    public function handle($request, Closure $next)
    {
        $country = ($request->hasHeader('country-id')) ? $request->header('country-id') : null;
        if (optional(getSettings('stop_app_all_countries'))->value == true) {
            return apiError(api('Our app is closed now'), STOP_MOBILE_APP);
        } else if (isset($country)) {
            $country = Country::findOrFail($country);
            if ($country->app_is_stopped == true) return apiError(api('Our app is closed now'), STOP_MOBILE_APP);
            else return $next($request);
        } else {
            return $next($request);

        }
    }
}
