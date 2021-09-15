<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Gift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class GiftController extends Controller
{
    private $_model;

    public function __construct(Gift $gift)
    {
        parent::__construct();
        $this->_model = $gift;
//        $this->middleware('permission:mecca pay', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) $this->validationRules["name.$local"] = 'required';
        foreach (config('translatable.locales') as $local) $this->validationRules["description.$local"] = 'required';
        $this->validationRules["points"] = 'required|numeric|min:1|max:100000';
    }

    public function index()
    {
        $title = t('Show Gifts');
        if (request()->ajax()) {
            $gifts = $this->_model->currentCountry(getCurrentCountry()->id);
            $search = request()->get('search', false);
            $gifts = $gifts->when($search, function ($query) use ($search) {
                $query->where('name->' . lang(), 'like', '%' . $search . '%');
            });
            return DataTables::make($gifts)
                ->escapeColumns([])
                ->addColumn('created_at', function ($gift) {
                    return Carbon::parse($gift->created_at)->toDateTimeString();
                })
                ->addColumn('name', function ($gift) {
                    return $gift->name;
                })
                ->addColumn('actions', function ($gift) {
                    return $gift->action_buttons;
                })
                ->make();
        }
        return view('manager.gift.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Gift');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.gift.edit', compact('title', 'validator'));
    }

    public function edit($id)
    {
        $title = t('Edit Gift');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $gift = $this->_model->with(['images'])->currentCountry(getCurrentCountry()->id)->findOrFail($id);
        return view('manager.gift.edit', compact('title', 'gift', 'validator'));
    }

    public function store(Request $request)
    {
        $store = (isset($request->gift_id)) ? $this->_model->currentCountry(getCurrentCountry()->id)->findOrFail($request->gift_id) : new $this->_model();
        $request->validate($this->validationRules);
//        dd(checkRequestIsWorkingOrNot());
        $store->name = $request->name;
        $store->description = $request->description;
        $store->points = $request->points;
        $store->country_id = getCurrentCountry()->id;
        $store->save();

        if (isset($request->images) && is_array($request->images)) {
            if (isset($request->gift_id)) $store->images()->delete();
            foreach ($request->images as $index => $image) $store->images()->create([
                'image' => $this->uploadImage($image, 'gifts')
            ]);
        }
        $message = isset($request->gift_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route('manager.gift.index')->with('m-class', 'success')->with('message', $message);
    }


    public function destroy($id)
    {
        $country = $this->_model->currentCountry(getCurrentCountry()->id)->findOrFail($id);
        $country->delete();
        return redirect()->route('manager.gift.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
