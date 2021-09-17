<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckIsNotBlocked
{

    public function handle($request, Closure $next)
    {
        if (apiUser()->status == User::user_status['Blocked']) return apiError(api('The account is Blocked'), UN_AUTHENTICATED);
        return $next($request);
    }
}
