<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\Controller;

use App\Http\Resources\Api\v1\Teacher\ProfileResource as TeacherProfile;
use App\Http\Resources\Api\v1\Student\ProfileResource as StudentsProfile;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function change_password(Request $request)
    {
        $request->validate(['password' => password_rules(true, 6, true)]);
        $user = user('student');
        if (!isset($user)) {
            $user = user('teacher');
            if (!isset($user)) return apiError(api('User Not Found'));
        }
//        if (!isset($user)) return apiError('User not found');
        $user->update(['password' => Hash::make($request->password)]);
        Auth::login($user);
        if (!$userToken = JWTAuth::fromUser($user)) return response()->json(['error' => 'invalid_credentials'], 401);
        $user['access_token'] = $userToken;

        return apiSuccess(($user instanceof Teacher) ? new TeacherProfile($user) : new StudentsProfile($user), apiTrans('Password Successfully Changed'));
    }

//    public function verifiy_account(Request $request)
//    {
//        $user = apiUser();
//        if ($user->verified == true) return apiError(api('Account Already vitrified'));
//        apiUser()->update(['verified' => true]);
//        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
//        return apiSuccess(($user instanceof Teacher) ? new TeacherProfile($user) :  new StudentsProfile($user) ,, api('Account vitrified successfully'));
//    }


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

//        dd(checkRequestIsWorkingOrNot());
        $request->validate([
            'name' => 'required|min:3|max:100',
            'phone' => ['required', 'unique:' . ($request->type == LOGIN_INFO_TYPES['STUDENT'] ? 'students' : 'teachers') . ',phone'],
            'password' => password_rules(true, 6, true),
            'type' => 'required|in:' . implode(',', LOGIN_INFO_TYPES),
        ]);
        return $request->type == LOGIN_INFO_TYPES['STUDENT'] ? $this->student_register($request) : $this->teacher_register($request);
    }

    public function forget_password(Request $request)
    {
        //        validations
        $request->validate(['phone' => 'required'/*|exists:students,phone*/]);
        $user = Student::where('phone', $request->phone)->first();
        if (!isset($user)) {
            $user = Teacher::where('phone', $request->phone)->first();
            if (!isset($user)) return apiError(api('Wrong phone'));
        }
        //        Generate code
        $sms_code = generateCode(0, 9, 6);
        $sms_code = CODE_FIXED;
        $user->update(['generatedCode' => $sms_code]);

//TODO Send sms to this mobile number
        return apiSuccess(($user instanceof Teacher) ? new TeacherProfile($user) : new StudentsProfile($user) , api('We sent to your SMS verification code, please check your phone'));
    }

    public function verified_code(Request $request)
    {
        $request->validate(['phone' => 'required'/*|exists:students,phone*/, 'code' => 'required|min:4|max:4',
        ]);


        $user = Student::where('phone', $request->phone)->first();
        if (!isset($user)) {
            $user = Teacher::where('phone', $request->phone)->first();
            if (!isset($user)) return apiError(api('Wrong phone'));
        }
//        if ($user->generatedCode != $request->get('code')) return apiError(apiTrans('You cannot login verified code invalid'));
        $user->update([
            'generatedCode' => null,
            'verified' => true,
        ]);
        Auth::login($user);

        if (!$userToken = JWTAuth::fromUser($user)) return response()->json(['error' => 'invalid_credentials'], 401);
        $user['access_token'] = $userToken;
        return apiSuccess(($user instanceof Teacher) ? new TeacherProfile($user) : new StudentsProfile($user), apiTrans('Successfully verified'));
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
        return apiSuccess(($user instanceof Teacher) ? new TeacherProfile($user) : new StudentsProfile($user));

    }

    public function logout()
    {
        auth()->logout();
        return apiSuccess('Successfully logged out');
    }

    protected function respondWithToken($token)
    {
        $user = user('student');
        $user['access_token'] = $token;
        return apiSuccess(($user instanceof Teacher) ? new TeacherProfile($user) : new StudentsProfile($user));
    }

}
