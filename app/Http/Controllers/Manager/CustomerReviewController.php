<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\CustomerReviews;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class CustomerReviewController extends Controller
{
    private $_model;
    private $view = 'manager.customer_reviews.';

    public function __construct(CustomerReviews $customerReviews)
    {
        parent::__construct();
        $this->_model = $customerReviews;
        $this->middleware('permission:Customer Reviews', ['only' => ['index', 'create', 'edit']]);
        $this->validationRules["rate"] = 'required|numeric|min:1|max:5';
        foreach (config('translatable.locales') as $local) $this->validationRules["title.$local"] = 'required|min:3|max:255';
        foreach (config('translatable.locales') as $local) $this->validationRules["details.$local"] = 'required|min:3|max:255';
        foreach (config('translatable.locales') as $local) $this->validationRules["customer_name.$local"] = 'required|min:3|max:255';
    }

    public function index()
    {
        $title = t('Show Customer Reviews');
        if (request()->ajax()) {
            $customerReviews = $this->_model->query();
            $search = request()->get('search', false);
//            $customerReviews = $customerReviews->when($search, function ($query) use ($search) {
//                $query->
////                where('rate', $search )
////                    ->or
//                where('title->' . lang(), 'like', '%' . $search . '%')
//                    ->orWhere('details->' . lang(), 'like', '%' . $search . '%')
//                    ->orWhere('customer_name->' . lang(), 'like', '%' . $search . '%');
//            });
            return DataTables::make($customerReviews)
                ->escapeColumns([])
                ->addColumn('created_at', function ($customerReview) {
                    return Carbon::parse($customerReview->created_at)->toDateTimeString();
                })
                ->addColumn('title', function ($customerReview) {
                    return $customerReview->title;
                })
                ->addColumn('details', function ($customerReview) {
                    return $customerReview->details;
                })
                ->addColumn('customer_name', function ($customerReview) {
                    return $customerReview->customer_name;
                })
                ->addColumn('actions', function ($customerReview) {
                    return $customerReview->action_buttons;
                })
                ->make();
        }
        return view($this->view . 'index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Customer Review');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view($this->view . 'edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $store = isset($request->customer_review_id) ? $this->_model->findOrFail($request->customer_review_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->title = $request->title;
        $store->details = $request->details;
        $store->customer_name = $request->customer_name;
        $store->rate = $request->rate;
        $store->save();
        $message = isset($request->customer_review_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route(CustomerReviews::manager_route . 'index')->with('m-class', 'success')->with('message', $message);
    }


    public function edit($id)
    {
        $title = t('Edit Customer Review');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $customerReview = $this->_model->query()->findOrFail($id);
        return view($this->view . 'edit', compact('title', 'customerReview', 'validator'));
    }


    public function destroy($id)
    {
        $customerReview = $this->_model->query()->findOrFail($id);
        $customerReview->delete();
        return redirect()->route(CustomerReviews::manager_route . 'index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
