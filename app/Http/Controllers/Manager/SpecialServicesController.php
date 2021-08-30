<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\SpecialService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class SpecialServicesController extends Controller
{
    private $_model;

    public function __construct(SpecialService $specialService)
    {
        parent::__construct();
        $this->_model = $specialService;
        $this->middleware('permission:Orders', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
    }

    public function index()
    {
        $title = t('Orders');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);

            $items = $this->_model->
            when($name, function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
                ->when($status != null, function ($query) use ($status) {
                    $query->where('draft', $status);
                });

            return DataTables::make($items)
                ->escapeColumns([])
                /*
                        {data: '', name: 'project_details'},
                        {data: 'service_type', name: 'service_type'},
                        {data: 'expected_budget', name: 'expected_budget'},
                        {data: 'expected_delivery_time', name: 'expected_delivery_time'},
                        {data: 'other_help_attachments', name: 'other_help_attachments'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
                 */
                ->addColumn('user_id', function ($item) {
                    return optional(optional($item)->user)->id;
                })
                ->addColumn('user_name', function ($item) {
                    return optional(optional($item)->user)->name;
                })
                ->addColumn('project_details', function ($item) {
                    return optional($item)->project_details;
                })
                ->addColumn('user_phone', function ($item) {
                    return optional(optional($item)->user)->phone;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        return view(SpecialService::manager_route . 'index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Package');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.package.edit', compact('title', 'validator'));
    }


    public function edit($id)
    {
        $title = t('Edit Package');
        $package = $this->_model->findOrFail($id);
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.package.edit', compact('title', 'validator', 'package'));
    }


    public function store(Request $request)
    {
        $store = (isset($request->package_id)) ? $this->_model->findOrFail($request->package_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->name = $request->name;
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->image, 'services');
        $store->save();
        if (isset($request->package_id)) {
            return redirect()->route('manager.package.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.package.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function show(Request $request,$id)
    {
        $item = $this->_model->findOrFail($id);
        return view(SpecialService::manager_route.'show', compact('item'));
    }
    public function destroy($id)
    {
        $item = $this->_model->findOrFail($id);
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


}
