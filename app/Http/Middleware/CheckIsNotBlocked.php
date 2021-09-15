<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckIsNotBlocked
{

    public function handle($request, Closure $next)
    {
        if (apiUser()->isBlocked == true) return apiError(api('The account is Blocked'), UN_AUTHENTICATED);
        return $next($request);
    }
}
