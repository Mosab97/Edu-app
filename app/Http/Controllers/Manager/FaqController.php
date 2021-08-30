<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Faq;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class FaqController extends Controller
{
    private $_model;

    public function __construct(Faq $faq)
    {
        parent::__construct();
        $this->_model = $faq;
        $this->middleware('permission:Faq', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) $this->validationRules["key.$local"] = 'required';
        foreach (config('translatable.locales') as $local) $this->validationRules["value.$local"] = 'required';
    }

    public function index()
    {
        $title = t('Show Faq');
        if (request()->ajax()) {
            $cities = $this->_model;
            $search = request()->get('search', false);
            $cities = $cities->when($search, function ($query) use ($search) {
                $query->where('key', 'like', '%' . $search . '%');
            });
            return DataTables::make($cities)
                ->escapeColumns([])
                ->addColumn('created_at', function ($faq) {
                    return Carbon::parse($faq->created_at)->toDateTimeString();
                })
                ->addColumn('key', function ($faq) {
                    return $faq->key;
                })
                ->addColumn('value', function ($faq) {
                    return $faq->value;
                })
                ->addColumn('actions', function ($faq) {
                    return $faq->action_buttons;
                })
                ->make();
        }
        return view('manager.faq.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add City');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.faq.edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $store = isset($request->faq_id) ? $this->_model->findOrFail($request->faq_id) : new $this->_model();
        $request->validate($this->validationRules);
//        dd(checkRequestIsWorkingOrNot());
        $store->key = $request->key;
        $store->value = $request->value;
//        $store->draft = $request->get('draft', 0);
        $store->save();
        if (isset($request->faq_id)) {
            return redirect()->route('manager.faq.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.faq.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function edit($id)
    {
        $title = t('Edit City');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $faq = $this->_model->query()->findOrFail($id);
        return view('manager.faq.edit', compact('title', 'faq', 'validator'));
    }


    public function destroy($id)
    {
        $faq = $this->_model->query()->findOrFail($id);
        $faq->delete();
        return redirect()->route('manager.faq.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
