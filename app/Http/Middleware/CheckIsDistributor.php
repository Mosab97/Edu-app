<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckIsDistributor
{

    public function handle($request, Closure $next)
    {
        $user = apiUser();
        if (isset($user) && $user->user_type != User::DISTRIBUTOR) return apiError(api('you have no permission'));
        return $next($request);
    }
}
