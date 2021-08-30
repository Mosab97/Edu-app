<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

// linkedin login
    public function redirectToProvider($provider)
    {
        
        if ($provider == 'linkedin') return Socialite::driver($provider)
                ->scopes(['r_liteprofile', 'r_emailaddress'])->redirect();
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            $authUser = $this->findOrCreateUser($user, $provider);
            Auth::login($authUser, true);
            return redirect($this->redirectTo);
        } catch (ClientException $exception) {
            return redirect('home');
        }
    }

    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) return $authUser;
        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
    }


    // Google login
    public function redirectToGoogle()
    {
       
        return Socialite::driver('google')->redirect();
    }

    // Google callback
    public function handleGoogleCallback()
    {
         
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        $existingUser = User::where('email', $user->email)->first();

        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser                           = new User();
            $newUser->name                     = $user->name;
            $newUser->email                    = $user->email;
            $newUser->google_provider_id       = $user->id;
            $newUser->image                    = $user->avatar;
            $newUser->save();
            auth()->login($newUser, true);

        }
        return redirect()->to('/home');

        // try {
        //     $user = Socialite::driver('google')->user();
        //     $this->_registerOrLoginUser($user);
        //     // Return home after login
        //     return redirect()->route('home');
        // } catch (\Exception $exception) {
        //     // dd($exception->getMessage());
        //     return redirect('home');
        // }
    }

    protected function _registerOrLoginUser($data)
    {
        $user = User::where('email', '=', $data->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->google_provider_id = $data->id;
            $user->image = $data->avatar;
            $user->save();
        }
        Auth::login($user);
    }


}
