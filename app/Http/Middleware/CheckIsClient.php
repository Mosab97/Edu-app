<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckIsClient
{

    public function handle($request, Closure $next)
    {
        $user = apiUser();
        if (isset($user) && ($user->user_type == User::CUSTOMER || $user->user_type == User::MERCHANT)) return $next($request);
        return apiError(api('you have no permission'));

    }
}
