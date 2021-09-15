<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AdImages;
use App\Models\Merchant;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class SliderController extends Controller
{
    private $_model;

    public function __construct(Slider $slider)
    {
        parent::__construct();
        $this->_model = $slider;
//        $this->middleware('permission:Slider', ['only' => ['index', 'create', 'edit', 'destroy']]);
        $this->validationRules["image"] = 'required|image';
        $this->validationRules["user_image"] = 'required|image';
        $this->validationRules["details"] = 'required|string|min:3|max:255';
    }

    public function index()
    {
        $title = t('Show Sliders');
        if (request()->ajax()) {
            $sliders = $this->_model->latest('updated_at');
            return DataTables::make($sliders)
                ->escapeColumns([])
                ->addColumn('created_at', function ($slider) {
                    return Carbon::parse($slider->created_at)->toDateTimeString();
                })
                ->addColumn('image', function ($slider) {
                    return '<img src="' . asset($slider->image) . '" width="50" height="50" />';
                })
                ->addColumn('actions', function ($slider) {
                    return $slider->action_buttons;
                })
                ->make();
        }
        return view('manager.slider.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Slider');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.slider.edit', compact('title', 'validator'));
    }

    public function edit($id)
    {
        $title = t('Edit Slider');
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["user_image"] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $slider = $this->_model->findOrFail($id);
        return view('manager.slider.edit', compact('title', 'slider', 'validator'));
    }

    public function store(Request $request)
    {
        if (isset($request->slider_id)) {
            $this->validationRules["image"] = 'nullable|image';
            $this->validationRules["user_image"] = 'nullable|image';
            $store = $this->_model->findOrFail($request->slider_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->file('image'), 'slider');
        if ($request->hasFile('user_image')) $store->user_image = $this->uploadImage($request->file('user_image'), 'slider');
        $store->details = $request->details;
        $store->save();
        if (isset($request->slider_id)) {
            return redirect()->route('manager.slider.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.slider.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function destroy($id)
    {
        $slider = $this->_model->findOrFail($id);
        $slider->delete();
        return redirect()->route('manager.slider.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
