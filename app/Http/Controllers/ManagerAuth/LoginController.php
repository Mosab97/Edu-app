<?php

namespace App\Http\Controllers\ManagerAuth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;

class LoginController extends Controller
{
    use AuthenticatesUsers;



    public $redirectTo = '/manager/home';

    public function __construct()
    {
        $this->middleware('manager.guest', ['except' => 'logout']);
    }


    public function showLoginForm()
    {
        return view('manager.auth.login');
    }

    public function username()
    {
        return 'email';
    }

    protected function authenticated(Request $request, $user)
    {
        session(['lang' => $user->local]);
        app()->setLocale($user->local);
        $country = $user->country;
        // Via a request instance... ... ..
        $request->session()->put('country', isset($country) ? $country : Country::first());
        //Or Via the global "session" helper...
//        session(['country' => $user->country_id]);
    }

    protected function guard()
    {
        return Auth::guard('manager');
    }
}
