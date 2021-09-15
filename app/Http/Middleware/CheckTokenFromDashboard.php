<?php

namespace App\Http\Middleware;

use Closure;

class CheckTokenFromDashboard
{

    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('token-from-dashboard')) return apiError(api('Api From Dashboard Is Required'));
        $token_setting = setting('token_form_dashboard');
        $token = $request->header('token-from-dashboard');
        return (strcasecmp($token_setting, $token) === 0) ? $next($request) : apiError(api('Wrong Api From Dashboard'));
    }
}
