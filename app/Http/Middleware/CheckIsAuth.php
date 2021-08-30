<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsAuth
{

    public function handle($request, Closure $next)
    {
        if (!Auth::guard('web')->check()) return redirect(route('login'));
        return $next($request);
    }
}
