<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class PackageController extends Controller
{
    private $_model;

    public function __construct(Package $package)
    {
        parent::__construct();
        $this->_model = $package;
        $this->middleware('permission:Packages', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) $this->validationRules["name.$local"] = 'required';
        $this->validationRules["price"] = 'required|numeric|min:1|max:1000';
    }

    public function index()
    {
        $title = t('Show Packages');
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
        return view(Package::manager_route . 'index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Package');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $categories = Category::get();
        return view(Package::manager_route . 'edit', compact('title', 'validator', 'categories'));
    }


    public function edit($id)
    {
        $title = t('Edit Package');
        $package = $this->_model->findOrFail($id);
//        $categories = Category::get();
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view(Package::manager_route . 'edit', compact('title', 'validator', 'package'));
    }


    public function store(Request $request)
    {
        $store = (isset($request->package_id)) ? $this->_model->findOrFail($request->package_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->price = $request->price;
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->image, 'packages');
        $store->save();
        $message = isset($request->package_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route(Package::manager_route . 'index')->with('m-class', 'success')->with('message', $message);
    }


    public function destroy($id)
    {
        $item = $this->_model->findOrFail($id);
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


}
