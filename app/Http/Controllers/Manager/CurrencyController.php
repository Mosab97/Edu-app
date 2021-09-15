<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller
{
    private $_model;

    public function __construct(Currency $currency)
    {
        parent::__construct();
        $this->_model = $currency;
//        $this->middleware('permission:Countries', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) $this->validationRules["name.$local"] = 'required';
    }

    public function index()
    {
        $title = t('Show Countries');
        if (request()->ajax()) {
            $currencies = $this->_model;
            $search = request()->get('search', false);
            $currencies = $currencies->when($search, function ($query) use ($search) {
                $query->where('name->' . lang(), 'like', '%' . $search . '%')->orWhere('code', 'like', '%' . $search . '%');
            });
            return DataTables::make($currencies)
                ->escapeColumns([])
                ->addColumn('created_at', function ($currency) {
                    return Carbon::parse($currency->created_at)->toDateTimeString();
                })
                ->addColumn('name', function ($currency) {
                    return $currency->name;
                })
                ->addColumn('image', function ($currency) {
                    return '<img src="' . asset($currency->image) . '" width="100" />';
                })
                ->addColumn('actions', function ($currency) {
                    return $currency->action_buttons;
                })
                ->make();
        }
        return view('manager.currency.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Currency');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $currencies = Currency::get();
        return view('manager.currency.edit', compact('title', 'currencies', 'validator'));
    }


    public function edit($id)
    {
        $title = t('Edit Currency');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $currency = $this->_model->findOrFail($id);
        return view('manager.currency.edit', compact('title', 'currency', 'validator'));
    }

    public function store(Request $request)
    {
        $store = (isset($request->currency_id)) ? $this->_model->findOrFail($request->currency_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->name = $request->name;
//        $store->code = $request->code;
        if (isset($request->image)) $store->image = $this->uploadImage($request->image, Currency::path);
        $store->draft = $request->get('draft', 0);
        $store->save();
        if (isset($request->currency_id)) {
            return redirect()->route('manager.currency.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.currency.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function destroy($id)
    {
        $currency = $this->_model->findOrFail($id);
        if ($currency->country()->count()) return redirect()->back()->with('m-class', 'error')->with('message',
            t('cannot delete Currency it has Countries'));
        $currency->delete();
        return redirect()->route('manager.currency.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
