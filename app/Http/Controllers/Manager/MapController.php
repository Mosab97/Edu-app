<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Classification;
use App\Models\Merchant;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class MapController extends Controller
{
    private $_model;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = t('Show Products');
        $orders = Order::get();
        return view('manager.map.index', compact('title','orders'));
    }

    public function create()
    {
        $title = t('Add Classification');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $restaurants = Merchant::get();
        return view('manager.classification.edit', compact('title', 'validator', 'restaurants'));
    }

    public function edit($id)
    {
        $title = t('Add Classification');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $restaurants = Merchant::get();
        $classification = Classification::with(['branch'])->findOrFail($id);
        $branches = Branch::currentMerchant($classification->merchant_id)->get();
//        $branchesIds = $classification->branches->pluck('id')->all();
        return view('manager.classification.edit', compact('title', 'validator', 'restaurants', 'classification'/*, 'branchesIds'*/, 'branches'));
    }

    public function store(Request $request)
    {
        if (isset($request->classification_id)) {
            $store = $this->_model->findOrFail($request->classification_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->merchant_id = $request->merchant_id;
        $store->branch_id = $request->branch_id;
        $store->draft = $request->get('draft', 0);
        $store->save();
//        $store->branches()->sync($request->branch_id);


        if (isset($request->classification_id)) {
            return redirect()->route('manager.classification.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.classification.index')->with('m-class', 'success')->with('message', t('Successfully Created'));

        }
    }

    public function destroy($id)
    {
        $classification = Classification::query()->findOrFail($id);
        if ($classification->items()->count()) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete classification it has meals'));
        }
        $classification->delete();
        return redirect()->route('manager.classification.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }

    public function classifications(Request $request)
    {
        $classifications = $this->_model->notDraft()
            ->whereHas('merchant', function ($query) use ($request) {
                $query->where('id', $request->merchant_id);
            })->whereHas('branch', function ($query) use ($request) {
                $query->where('id', $request->branch_id);
            })->get();
        $html = '<option value="" disabled selected>' . t('Select Classification') . '</option>';
        foreach ($classifications as $classification) {
            $html .= '<option value="' . $classification->id . '">' . $classification->name . '</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function branchClassifications(Request $request)
    {
        $classifications = $this->_model->notDraft()->where('branch_id', $request->id)->get();
        $html = '<option value="" disabled selected>' . t('Select Classification') . '</option>';
        foreach ($classifications as $classification) {
            $html .= '<option value="' . $classification->id . '">' . $classification->name . '</option>';
        }
        return response()->json(['html' => $html]);
    }
}
