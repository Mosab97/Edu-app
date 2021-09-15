<?php

namespace App\Http\Middleware;

use Closure;

class CheckHasCountry
{
    public function handle($request, Closure $next)
    {
//        dd($request->header('country-id'),$request->header('Content-Language'));
        $country = ($request->hasHeader('country-id')) ? $request->header('country-id') : null;
      if ($country == null || $country == '') return  apiError(api('Please send your country'));
        return $next($request);
    }
}
