<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard )
    {

        if($guard != null ){
            auth()->shouldUse($guard); //shoud you user guard / table
//            $token = $request->header('X-Authorization');
            $token = $request->header('Authorization');
//            dd($token);
            $request->headers->set('token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer '.$token, true);
            try {
//                  $user = $this->auth->authenticate($request); //check authenticted user
                $user = JWTAuth::parseToken()->authenticate();
            } catch (TokenExpiredException $e) {
                return apiError($e->getMessage(),401);
//                return   response()->json([
//                    'code' => 0,
//                    'message' => 'failed',
//                    'errors' => [
//
//                    ],
//                    'data' => null
//                ] , 401);
            }

        }
        return $next($request);
    }
}
