<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    private $_model;

    public function __construct(Service $service)
    {
        parent::__construct();
        $this->_model = $service;
        $this->middleware('permission:Services', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) $this->validationRules["name.$local"] = 'required';
    }

    public function index()
    {
        $title = t('Show Services');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);

            $items = $this->_model->when($name, function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
                ->when($status != null, function ($query) use ($status) {
                    $query->where('draft', $status);
                });

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        return view('manager.service.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Service');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.service.edit', compact('title', 'validator'));
    }


    public function edit($id)
    {
        $title = t('Edit Service');
        $service = $this->_model->findOrFail($id);
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.service.edit', compact('title', 'validator', 'service'));
    }


    public function store(Request $request)
    {
        $store = (isset($request->service_id)) ? $this->_model->findOrFail($request->service_id) : new $this->_model();
        $request->validate($this->validationRules);
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->image, 'services');
        $store->name = $request->name;
        $store->save();
        if (isset($request->service_id)) {
            return redirect()->route('manager.service.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.service.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function destroy($id)
    {
        $item = $this->_model->findOrFail($id);
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


}
