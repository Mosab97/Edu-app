<?php

namespace App\Http\Controllers\Api\v1\Adminapiapp;

use App\Http\Controllers\Api\v1\Controller;

use App\Http\Resources\AdminApi\ProfileResource;
use App\Http\Resources\AdminApiProfileResource;
use App\Models\Adminapiapp;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    private $model;

    public function __construct(Adminapiapp $adminapiapp)
    {
        $this->model = $adminapiapp;
    }

    public function login(Request $request)
    {
        //        validations
        $request->validate([
            'email' => ['required', new EmailRule(), 'exists:adminapiapps,email'],
            'password' => password_rules(true, 6),
        ]);
        $user = $this->model->where('email', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) return apiError(api('Wrong User Password'));
        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
        return apiSuccess(new ProfileResource($user), api('Successfully Login'));
    }

}
