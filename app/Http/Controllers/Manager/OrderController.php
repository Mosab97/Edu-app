<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    private $_model;

    public function __construct(Order $order)
    {
        parent::__construct();
        $this->_model = $order;
        $this->middleware('permission:Orders', ['only' => ['index', 'create', 'edit']]);
    }

    public function index()
    {
        $title = t('Orders');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);

            $items = $this->_model->when($name, function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('user_id', function ($item) {
                    return optional(optional($item)->user)->id;
                })
                ->addColumn('user_name', function ($item) {
                    return optional(optional($item)->user)->name;
                })
                ->addColumn('user_phone', function ($item) {
                    return optional(optional($item)->user)->phone;
                })
                ->addColumn('service_name', function ($item) {
                    return optional(optional($item)->service)->name;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        return view(Order::manager_route . 'index', compact('title'));
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
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->image, 'packages');
        $store->save();
        if (isset($request->package_id)) {
            return redirect()->route('manager.package.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.package.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }

    public function show(Request $request, $id)
    {
        $order = $this->_model->findOrFail($id);
        return view(Order::manager_route . 'show', compact('order'));
    }

    public function destroy($id)
    {
        $item = $this->_model->findOrFail($id);
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


}
