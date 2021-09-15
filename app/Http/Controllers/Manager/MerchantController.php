<?php

namespace App\Http\Controllers\Manager;

use App\Events\AddNewBalanceEvent;
use App\Events\UserNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\AcceptMerchantFromManagerNotification;
use App\Notifications\DistributorCancelOrderNotification;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class MerchantController extends Controller
{
    private $_model;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->_model = $user;
//        $this->middleware('permission:Merchant', ['only' => ['index', 'create', 'edit', 'show', 'destroy']]);

        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,{$id},id,NULL'];
//        $this->validationRules["password"] = password_rules(true, 6);

        $this->validationRules["city_id"] = 'required|exists:cities,id';
        $this->validationRules["image"] = 'nullable|image';
    }

    public function index()
    {
        $title = t('Show Users');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);
            $source = request()->get('source', false);

            $items = $this->_model->merchantUserType()->searchName($name)
//                ->when($name, function ($query) use ($name) {
//                $query->where('first_name', 'like', '%' . $name . '%')
//                    ->orWhere('last_name', 'like', '%' . $name . '%');
//            })
                ->when($status != null, function ($query) use ($status) {
                    $query->where('isBlocked', $status);
                })->currentCountry(getCurrentCountry()->id);
            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('status_name', function ($item) {
                    return $item->status_name;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        return view('manager.merchant.index', compact('title'));
    }

    public function edit($id)
    {
        $title = t('Edit Merchant');
        $merchant = $this->_model->merchantUserType()->findOrFail($id);
        return view('manager.merchant.edit', compact('title', 'merchant'));
    }

    public function store(Request $request)
    {
        $store = $this->_model->merchantUserType()->findOrFail($request->merchant_id);
        $store->isBlocked = !(boolean)$request->get('active', 0);
        $store->active = (boolean)$request->get('active', 0);
        $store->save();
        if (isset($request->active)) \Illuminate\Support\Facades\Notification::send($store, new AcceptMerchantFromManagerNotification());
        return redirect()->route('manager.merchant.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
    }

    public function show($id)
    {
        $title = t('Show Merchant');
        $merchant = $this->_model->with('merchant')->merchantUserType()->findOrFail($id);

        $this->validationRules['amount'] = 'required|gt:0';
        $this->validationRules['amount'] = 'required|gt:0';
        $validator = JsValidator::make($this->validationRules);

        $this->validationRules["title"] = 'required';
        $this->validationRules["content"] = 'required';
        $this->validationRules["user_id"] = 'required';
        $notify_validator = JsValidator::make($this->validationRules, $this->validationMessages);

        return view('manager.merchant.show', compact('merchant', 'title', 'notify_validator', 'validator'));
    }

    public function destroy($id)
    {
        $item = $this->_model->merchantUserType()->findOrFail($id);
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
