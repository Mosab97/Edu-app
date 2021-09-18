<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\Controller;

use App\Http\Resources\Api\v1\General\ProfileResource;
use App\Models\User;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }


    public function login(Request $request)
    {
        //        validations
        $request->validate([
            'phone' => ['required', 'numeric'],
            'password' => password_rules(true, 6),
        ]);
        $user = $this->model->query()->phone(request('phone'))->first();
        if (!isset($user)) return apiError(api('Wrong mobile Number'));
        if (!Hash::check($request->password, $user->password)) return apiError(api('Wrong User Password'));
        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
        return apiSuccess(new ProfileResource($user), api('Successfully Logedin'));
    }


    public function change_password(Request $request)
    {
        $request->validate(['password' => password_rules(true, 6, true)]);
        $user = apiUser();
        apiUser()->update(['password' => Hash::make($request->password)]);
        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
        return apiSuccess(new ProfileResource($user), api('Account vitrified successfully'));
    }

    public function verifiy_account(Request $request)
    {
        $user = apiUser();
        if ($user->verified == true) return apiError(api('Account Already vitrified'));
        apiUser()->update(['verified' => true]);
        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
        return apiSuccess(new ProfileResource($user), api('Account vitrified successfully'));
    }

    public function forget_password(Request $request)
    {
        //        validations
        $request->validate(['phone' => 'required|exists:users,phone']);
        $user = User::where('phone', $request->phone)->first();
        if (!isset($user)) return apiError(api('Wrong Phone Number'));
        //        Generate code
//        $sms_code = generateCode(0, 9, 6);
        $sms_code = CODE_FIXED;
        $user->update(['generatedCode' => $sms_code]);
        return apiSuccess(null, api('We sent Phone Number verification code, please check your phone'));
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:100',
            'phone' => ['required', 'unique:users,phone'],
            'password' => password_rules(true, 6, true),
            'type' => 'required|in:' . implode(',', User::user_type),
        ]);
        $data = $request->except(['password', 'password_confirmation', 'type']);
        $data['password'] = Hash::make($request->password);
        $data['user_type'] = $request->type;
        $data['verified'] = false;
        $data['status'] = User::user_status['Accepted'];
        $data['generatedCode'] = generateCode();
        $student = User::create($data);
        $student['access_token'] = $student->createToken(API_ACCESS_TOKEN_NAME)->accessToken;

        return apiSuccess(new ProfileResource($student), api('Successfully Registered'));
    }

    public function verified_code(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'code' => 'required|min:4|max:4',
        ]);
        $user = $this->model->where('phone', $request->get('phone'))->first();
        if ($user->generatedCode != $request->get('code')) return apiError(apiTrans('You cannot login verified code invalid'));
        $user->update([
            'generatedCode' => null,
            'verified' => true,
        ]);
        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
        return apiSuccess(new ProfileResource($user), apiTrans('Successfully verified'));
    }

    public function resend_code(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
        ]);
        $user = $this->model->where('phone', $request->get('phone'))->first();
        $user->update([
            'generatedCode' => CODE_FIXED,
            'verified' => false,
        ]);
        return apiSuccess([
            'code' => CODE_FIXED
        ], api('We sent Phone Number verification code, please check your phone'));
    }

    public function logoutAllAuthUsers()
    {
        return apiSuccess(logoutAllAuthUsers());
    }

    public function logout(Request $request)
    {
        $user = apiUser();
        if (!isset($user)) return apiSuccess(null, apiTrans('please login'));
        $user->tokens()->delete();
        return apiSuccess(null, apiTrans('Successful Logout'));
    }
}
