<?php

namespace App\Http\Controllers\Manager;

use App\Events\AddNewBalanceEvent;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Address;
use App\Models\Notification;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\GeneralNotification;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    private $_model;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->_model = $user;
        $this->middleware('permission:Users', ['only' => ['index', 'create', 'edit', 'show', 'destroy']]);

        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,{$id},id,deleted_at,NULL'];

        $this->validationRules["city_id"] = 'nullable|exists:cities,id';
        $this->validationRules["image"] = 'nullable|image';
    }

    public function index()
    {
        $title = t('Show Users');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);
            $source = request()->get('source', false);

            $items = $this->_model->when($name, function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
                ->when($status != null, function ($query) use ($status) {
                    $query->where('status', $status);
                })
                ->when($source != null, function ($query) use ($source) {
                    $query->where('source', $source);
                })
//                ->withoutGlobalScope('notDraft')
                ->latest();

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('mobile', function ($item) {
                    return $item->phone;
                })
                ->addColumn('source_name', function ($item) {
                    return $item->source_name;
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
        return view('manager.user.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Client');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.user.edit', compact('title', 'validator'));
    }

    public function edit($id)
    {
        $title = t('Edit Client');
        $client = $this->_model->findOrFail($id);

        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,' . $client->id . ',id,deleted_at,NULL'];
//        $this->validationRules["password"] = 'nullable|min:6';
        $this->validationRules["image"] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.user.edit', compact('title', 'validator', 'client'));
    }


    public function store(Request $request)
    {
        if (isset($request->client_id)) {
            $store = $this->_model->findOrFail($request->client_id);
            $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,' . $store->id . ',id,deleted_at,NULL'];
//            $this->validationRules["password"] = 'nullable|min:6';
            $this->validationRules["image"] = 'nullable|image';
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->username = $request->username;
        $store->country = $request->country;
        $store->city = $request->city;
        $store->client_type = $request->client_type;
        $store->url = $request->url;
        $store->source = User::DASHBOARD;
        $store->draft = $request->get('draft', 0);
        $store->verified = $request->get('active', false);
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->file('image'), 'Users');
        if ($request->password) $store->password = Hash::make($request->password);
        $store->save();

        if (isset($request->client_id)) {
            return redirect()->route('manager.user.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.user.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function show($id)
    {
        $title = t('Show Client');
        $user = $this->_model->findOrFail($id);
        $data['wallet_balance'] = $user->user_wallet;
        $data['orders_count'] = Order::where('user_id', $id)->count();
        $data['addresses_count'] = Address::where('user_id', $id)->count();
        $data['rates_count'] = DriverClientRate::where('user_id', $id)->count();
        $this->validationRules['amount'] = 'required|gt:0';
        $this->validationRules['amount'] = 'required|gt:0';
        $validator = JsValidator::make($this->validationRules);

        $this->validationRules["title"] = 'required';
        $this->validationRules["content"] = 'required';
        $this->validationRules["user_id"] = 'required';
        $notify_validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.user.show', compact('user', 'title', 'data', 'notify_validator', 'validator'));
    }


    public function destroy($id)
    {
        $item = $this->_model->findOrFail($id);
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


}
