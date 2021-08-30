<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\Controller;

use App\Http\Resources\Api\v1\User\ProfileResource;
use App\Models\Country;
use App\Models\Merchant;
use App\Models\ShareApp;
use App\Models\User;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
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
        ]);
        $user = User::phone(request('phone'))->first();
        if (!isset($user)) return apiError(api('Wrong mobile Number'));
        if (!Hash::check($request->password, $user->password)) return apiError(api('Wrong User Password'));
//        dd(34);
        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;

        return apiSuccess(new ProfileResource($user), apiTrans('Successfully Logedin'));
    }

    public function register(Request $request)
    {

        $request->validate([
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'country_id' => 'required|exists:countries,id',
            'mobile' => ['required', 'numeric', 'unique:users,mobile'],
            "email" => ['required', 'unique:users,email', new EmailRule()],
            'password' => password_rules(true, 6),
            'user_type' => 'required|in:' . User::CUSTOMER . ',' . User::MERCHANT . ',' . User::DISTRIBUTOR,
            'merchant_type' => 'requiredIf:user_type,' . User::MERCHANT . '|in:' . Merchant::MERCHANT_TYPES['RETAILER'] . ',' . Merchant::MERCHANT_TYPES['WHOLESALER'],
            'store_name' => 'requiredIf:user_type,' . User::MERCHANT,
            'lat' => 'requiredIf:user_type,' . User::MERCHANT,
            'lng' => 'requiredIf:user_type,' . User::MERCHANT,
            'office_mobile' => ['requiredIf:user_type,' . User::MERCHANT,/* 'numeric', 'unique:users,office_mobile,{$id},id,deleted_at,NULL'*/],
            'retailer_merchant_type' => 'requiredIf:merchant_type,' . Merchant::MERCHANT_TYPES['RETAILER'] . '|in:' . Merchant::RETAILER_MERCHANT_TYPES['CAFETERIA']
                . ',' . Merchant::RETAILER_MERCHANT_TYPES['MINIMARKET'] . ',' . Merchant::RETAILER_MERCHANT_TYPES['SUPERMARKET'],
        ]);

        $user = new User();
        $user->country_id = $request->country_id;
        $user->user_type = $request->user_type;
        $user->first_name = [
            'ar' => $request->first_name,
            'en' => $request->first_name,
        ];
        $user->last_name = [
            'ar' => $request->last_name,
            'en' => $request->last_name,
        ];
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->lat = $request->lat;
        $user->lng = $request->lng;
        $user->local = 'ar';
        $user->verified = false;
        $user->isBlocked = false;
        $country = Country::findOrFail($request->country_id);
        $user->points = $request->user_type == User::CUSTOMER ? $country->customer_points : $country->merchant_points;
        $user->active = $request->user_type == User::MERCHANT ? false : true;

        $user->generatedCode = generateCode();
        $user->save();


        $shareApp = ShareApp::whereNull("new_registered_user_id")->where('mobile', $request->mobile)->first();
        if (isset($shareApp)) $shareApp->update([
            'new_registered_user_id' => $user->id
        ]);

        if ($request->user_type == User::MERCHANT) $user->merchant()->create([
            'office_mobile' => $request->office_mobile,
            'merchant_type' => $request->merchant_type,
            'store_name' => $request->store_name,
            'retailer_merchant_type' => $request->retailer_merchant_type,
        ]);
        if ($request->user_type == User::DISTRIBUTOR) $user->distributor()->create([
            'receive_orders' => true,
        ]);

        if ($request->hasFile('image')) $user->image = uploadImage($request->file('image'), User::DIR_IMAGE_UPLOADS);
        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
        return apiSuccess(new ProfileResource($user));
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
