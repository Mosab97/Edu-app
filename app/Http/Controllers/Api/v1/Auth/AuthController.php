<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\Controller;

use App\Http\Resources\Api\v1\User\ProfileResource;
use App\Models\Country;
use App\Models\Merchant;
use App\Models\ShareApp;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mail;

class AuthController extends Controller
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function change_password(Request $request)
    {
        $request->validate(['password' => password_rules(true, 6),]);
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
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();
        if (!isset($user)) return apiError(api('Wrong Email Address'));
        //        Generate code
        $sms_code = generateCode(0, 9, 6);
//        $sms_code = CODE_FIXED;
        $user->update(['generatedCode' => $sms_code]);

        Mail::send('/emails/forget_password', ['user' => $user], function ($m) use ($user) {
            $m->to($user->email)->subject(trans('Verification Code'))->getSwiftMessage()
                ->getHeaders()
                ->addTextHeader('x-mailgun-native-send', 'true');
        });

//        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ForgetPasswordEmail($user));
        return apiSuccess(null, api('We sent to your email verification code, please check your email'));
    }

    public function login(Request $request)
    {
        //        validations
        $request->validate([
            'phone' => ['required', 'numeric'],
            'password' => password_rules(true, 6),
            'type' => 'required|in:' . implode(',', LOGIN_INFO_TYPES),
        ]);
        return $request->type == LOGIN_INFO_TYPES['STUDENT'] ? $this->student_login($request) : $this->teacher_login($request);
    }


    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|min:3|max:100',
            'phone' => ['required', 'numeric', 'unique:' . ($request->type == LOGIN_INFO_TYPES['STUDENT'] ? 'students' : 'teachers') . ',phone'],
            'password' => password_rules(true, 6, true),
            'type' => 'required|in:' . implode(',', LOGIN_INFO_TYPES),
        ]);
        return $request->type == LOGIN_INFO_TYPES['STUDENT'] ? $this->student_register($request) : $this->teacher_register($request);
    }

    public function verified_code(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|min:4|max:4',
        ]);
        $user = $this->model->where('email', $request->get('email'))->first();
        if ($user->generatedCode != $request->get('code')) return apiError(apiTrans('You cannot login verified code invalid'));
        $user->update([
            'generatedCode' => null,
            'verified' => true,
        ]);
        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
        return apiSuccess(new ProfileResource($user), apiTrans('Successfully verified'));
    }


    private function student_login(Request $request)
    {
        $credentials = request(['phone', 'password']);
        if (!$token = auth("student")->attempt($credentials))
            return apiError('Unauthorized', 401);
        return $this->respondWithToken($token);
    }

    private function student_register(Request $request)
    {
        $data = $request->except(['password', 'password_confirmation', 'type']);
        $data['password'] = Hash::make($request->password);
        $student = Student::create($data);
        return $this->student_login($request);
    }
    private function teacher_register(Request $request)
    {
        $data = $request->except(['password', 'password_confirmation', 'type']);
        $data['password'] = Hash::make($request->password);
        $student = Teacher::create($data);
        return $this->student_login($request);
    }

    private function teacher_login(Request $request)
    {
        $credentials = request(['phone', 'password']);
        if (!$token = auth("teacher")->attempt($credentials))
            return apiError('Unauthorized', 401);
        $user = user('teacher');
        $user['access_token'] = $token;
        return apiSuccess(new ProfileResource($user));

    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        $user = user('student');
        $user['access_token'] = $token;
        return apiSuccess(new ProfileResource($user));
    }

}
