<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AdImages;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class AdImagesController extends Controller
{
    private $_model;

    public function __construct(AdImages $adImages)
    {
        parent::__construct();
        $this->_model = $adImages;
//        $this->middleware('permission:Ad Images', ['only' => ['index', 'create', 'edit', 'destroy']]);
        $this->validationRules["image"] = 'required|image';
    }

    public function index()
    {
        $title = t('Show Ad Images');
        if (request()->ajax()) {
            $sliders = $this->_model->currentCountry(getCurrentCountry()->id);
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
        return view('manager.ad_images.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Ad Images');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.ad_images.edit', compact('title', 'validator'));
    }

    public function edit($id)
    {
        $title = t('Edit Ad Images');
        $this->validationRules["image"] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $slider = $this->_model->currentCountry(getCurrentCountry()->id)->findOrFail($id);
        return view('manager.ad_images.edit', compact('title', 'slider', 'validator'));
    }

    public function store(Request $request)
    {
        if (isset($request->slider_id)) {
            $this->validationRules["image"] = 'nullable|image';
            $store = $this->_model->currentCountry(getCurrentCountry()->id)->findOrFail($request->slider_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->country_id = getCurrentCountry()->id;
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->file('image'), 'slider');
        $store->save();
        if (isset($request->slider_id)) {
            return redirect()->route('manager.ad_images.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.ad_images.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function destroy($id)
    {
        $slider = $this->_model->currentCountry(getCurrentCountry()->id)->findOrFail($id);
        $slider->delete();
        return redirect()->route('manager.ad_images.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
