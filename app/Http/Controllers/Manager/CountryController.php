<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    private $_model;

    public function __construct(Country $country)
    {
        parent::__construct();
        $this->_model = $country;
//        $this->middleware('permission:Countries', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) $this->validationRules["name.$local"] = 'required';
        $this->validationRules['currency_id'] = 'required|exists:currencies,id';
        $this->validationRules['kilo_cost'] = 'required';
        $this->validationRules['zip'] = 'required';
        $this->validationRules['vat'] = 'required';
        $this->validationRules['email'] = 'required|email';
        $this->validationRules['mobile'] = 'required';
        $this->validationRules['address'] = 'required';
        $this->validationRules['default_speed'] = 'required';
        $this->validationRules['new_registered_users_discount_percent'] = 'required';
        $this->validationRules['new_registered_users_discount_time'] = 'required';
        $this->validationRules['customer_points'] = 'required';
        $this->validationRules['merchant_points'] = 'required';
    }

    public function index()
    {
        $title = t('Show Countries');
        if (request()->ajax()) {
            $countries = $this->_model;
            $search = request()->get('search', false);
            $countries = $countries->when($search, function ($query) use ($search) {
                $query->where('name->' . lang(), 'like', '%' . $search . '%');
            });
            return DataTables::make($countries)
                ->escapeColumns([])
                ->addColumn('created_at', function ($country) {
                    return Carbon::parse($country->created_at)->toDateTimeString();
                })
                ->addColumn('name', function ($country) {
                    return $country->name;
                })
                ->addColumn('actions', function ($country) {
                    return $country->action_buttons;
                })
                ->make();
        }
        return view('manager.country.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Country');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $currencies = Currency::get();
        return view('manager.country.edit', compact('title', 'currencies', 'validator'));
    }


    public function edit($id)
    {
        $title = t('Edit Country');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $country = $this->_model->findOrFail($id);
        $currencies = Currency::get();
        return view('manager.country.edit', compact('title', 'country', 'currencies', 'validator'));
    }

    public function store(Request $request)
    {
        $store = isset($request->country_id) ? $this->_model->findOrFail($request->country_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->email = $request->email;
        $store->mobile = $request->mobile;
        $store->max_distance = $request->max_distance;
        $store->address = $request->address;
        $store->currency_id = $request->currency_id;
        $store->kilo_cost = $request->kilo_cost;
        $store->zip = $request->zip;
        $store->vat = $request->vat;
        $store->default_speed = $request->default_speed;
        $store->new_registered_users_discount_percent = $request->new_registered_users_discount_percent;
        $store->new_registered_users_discount_time = $request->new_registered_users_discount_time;
        $store->customer_points = $request->customer_points;
        $store->merchant_points = $request->merchant_points;
        $store->draft = $request->get('draft', 0);
        $store->save();
        $message = isset($request->country_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route('manager.country.index')->with('m-class', 'success')->with('message', $message);
    }


    public function destroy($id)
    {
        $country = $this->_model->findOrFail($id);
        if ($country->products()->count()) return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete Country it has products'));
        $country->delete();
        return redirect()->route('manager.country.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
