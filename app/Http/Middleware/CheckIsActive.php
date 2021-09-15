<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckIsActive
{

    public function handle($request, Closure $next)
    {
        $user = apiUser();
        if (!$user->active) return apiError(api('The account is not activated'), WAITING);
        return $next($request);
    }
}
