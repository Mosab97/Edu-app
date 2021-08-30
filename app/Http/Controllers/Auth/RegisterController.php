<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $this->validationRules["name"] = ['required', 'string', 'max:255'];
        $this->validationRules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email'];
        $this->validationRules['phone'] = ['required', 'string', 'max:255', 'unique:users,phone'];
        $this->validationRules['password'] = ['required', 'string', 'min:8'];
        $this->validationRules['country'] = ['required', 'string', 'max:100'];
        $this->validationRules['city'] = ['required', 'string', 'max:100'];
        $this->validationRules['client_type'] = ['required', 'numeric', 'in:' . User::client_type['CLIENT'] . ',' . User::client_type['COMPANY']];
        $this->validationRules['username'] =['required', 'string', 'max:255', 'unique:users,username'];
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('auth.register', compact('validator'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    protected function create(array $data)
    {
//        dd($data);
        return User::create([
            'name' => [
                'ar' => $data['name'],
                'en' => $data['name'],
            ],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'country' => $data['country'],
            'city' => $data['city'],
            'client_type' => $data['client_type'],
//            'url' => $data['url'],
            'username' => $data['username'],
        ]);
    }
}
