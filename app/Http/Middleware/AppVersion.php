<?php

namespace App\Http\Middleware;

use Closure;

class AppVersion
{

    public function handle($request, Closure $next)
    {
        $appVersion = ($request->hasHeader('app-version')) ? $request->header('app-version') : null;
        if ($appVersion == null || $appVersion == '') return apiError(api('Please send your App Version'));
        if (optional(getSettings('app_version'))->value != $appVersion) return apiError(api('Please Update Your App'), UPDATE_APP_VERSION);
        return $next($request);
    }
}
