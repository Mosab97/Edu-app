<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductPrices;
use App\Models\Setting;
use App\Models\User;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->middleware('permission:General Settings', ['only' => ['settings', 'updateSettings']]);
//        $this->middleware('permission:Dashboard', ['only' => ['home']]);
    }

    public function generate_admin_app_api_new_token(Request $request)
    {
        setting(['token_form_dashboard' => generateRandomString()])->save();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Token Generated Successfully'));

    }

    public function home()
    {
        $country = getCurrentCountry();
        $total_earning = Order::completedOrder()->sum('total_cost');
        $today_earning = Order::completedOrder()->whereDay('created_at', date('d'))->sum('total_cost');
        $total_active_users = User::count();
        $total_orders = Order::count();
        $total_products = ProductPrices::currentCountry(getCurrentCountry()->id)->count();
//        $total_opened_tickets = Order::whereHas('chatMessages')->count();
        $total_opened_tickets = Order::where('has_chat', true)->count();
        return view('manager.home', compact('total_earning', 'today_earning', 'total_active_users', 'total_orders', 'total_products', 'total_opened_tickets'));

    }

    public function changeCountry(Request $request, int $id,$segments = 0)
    {
        $user = user();
        $user->country_id = $id;
        $user->save();
//        $user->update(['country_id', $id]);
        $request->session()->put('country', Country::findOrFail($id));
        return $segments == 4 ? redirect()->back() : redirect()->route('manager.home');
    }

    public function settings()
    {
        $title = t('Show Settings');
        return view('manager.setting.general', compact('title'));
    }


    public function updateSettings(Request $request)
    {
//        dd(checkRequestIsWorkingOrNot());
        $data = $request->except(['_token', 'logo', 'logo_min']);
        if ($request->hasFile('logo')) {
            if ($request->file('logo')->isValid()) {
                setting(['logo' => $this->uploadImage($request->file('logo'), 'logos')])->save();
            }
        }
        if ($request->hasFile('logo_min')) {
            if ($request->file('logo_min')->isValid())
                setting(['logo_min' => $this->uploadImage($request->file('logo_min'), 'logo_min')])->save();
        }
        foreach ($data as $index => $datum) {
            if (is_array($datum) && sizeof($datum) > 0) {
                foreach ($datum as $index2 => $item) {
                    if (is_null($item)) {
                        $datum[$index2] = '';
                    }
                }
            }
            if (is_null($datum)) $datum = '';
            else setting([$index => $datum])->save();
        }
        setting(['stop_app_all_countries' => $request->get('stop_app_all_countries', 0)])->save();
        optional(getCurrentCountry())->update(['app_is_stopped' => $request->get('stop_app_for_current_country', 0)]);
        Artisan::call('cache:clear');
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');

        $data = $request->all();
        $setting = Setting::query()->findOrNew('1');
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadImage($request->file('logo'), 'logos');
        }
        if ($request->hasFile('logo_min')) {
            $data['logo_min'] = $this->uploadImage($request->file('logo_min'), 'logos');
        }
        $setting->update($data);
        Artisan::call('cache:clear');
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function lang($local)
    {
        session(['lang' => $local]);
        if (Auth::guard('manager')->check()) {
            $user = Auth::guard('manager')->user();
            $user->update([
                'local' => $local,
            ]);
        }
        app()->setLocale($local);
        return back();
    }

    public function view_profile()
    {
        $title = t('Show Profile');
        return view('manager.setting.profile', compact('title'));
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('manager')->user();
        $this->validationRules['password'] = 'nullable';
        $this->validationRules['email'] = ['required', 'unique:managers,email,' . $user->id, new EmailRule()];
        $request->validate($this->validationRules);

        $data = $request->all();
        if ($request->has(['password', 'password_confirmation']) && !empty($request->get('password'))) {
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $user->password;
        }
        $user->update($data);
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }
}
