<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Package;
use App\Models\PackageValues;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class PackageValuesController extends Controller
{
    private $_model;

    public function __construct(PackageValues $packageValues)
    {
        parent::__construct();
        $this->_model = $packageValues;
        $this->middleware('permission:Package Values', ['only' => ['index', 'create', 'edit']]);
    }

    public function index()
    {
        $title = t('Show Package Values');
        if (request()->ajax()) {
            $package = request()->get('package', false);
            $name = request()->get('name', false);

            $items = $this->_model->with(['package'])->when($package, function ($query) use ($package) {
                $query->where('package_id', $package);
            })
                ->when($name, function ($query) use ($name) {
                    $query->whereHas('package', function ($qq) use ($name) {
                        $qq->where('name->' . lang(), 'like', '%' . $name . '%');
                    });

                });

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('package_name', function ($item) {
                    return optional(optional($item)->package)->name;
                })
                ->addColumn('value', function ($item) {
                    return optional($item)->value;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        $packages = Package::get();
        return view(PackageValues::manager_route . 'index', compact('title', 'packages'));
    }

    public function create()
    {
        $title = t('Add Category');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $packages = Package::get();
        return view(PackageValues::manager_route . 'edit', compact('title', 'validator', 'packages'));
    }


    public function edit($id)
    {
        $title = t('Edit Category');
        $package_value = $this->_model->findOrFail($id);
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $packages = Package::get();
        return view(PackageValues::manager_route . 'edit', compact('title', 'validator', 'packages', 'package_value'));
    }


    public function store(Request $request)
    {
//        dd(checkRequestIsWorkingOrNot());
        $store = (isset($request->package_value_id)) ? $this->_model->findOrFail($request->package_value_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->package_id = $request->package;
        $store->value = $request->value;
        $store->save();
        $message = isset($request->package_value_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route(PackageValues::manager_route . 'index')->with('m-class', 'success')->with('message', $message);
    }


    public function destroy($id)
    {
        $item = $this->_model->findOrFail($id);
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


}
