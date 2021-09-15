<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductPrices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class OfferController extends Controller
{
    private $_model;

    public function __construct(Offer $offer)
    {
        parent::__construct();
        $this->_model = $offer;
//        $this->middleware('permission:Product Offers', ['only' => ['index', 'create', 'edit']]);
    }

    public function index()
    {
        $title = t('Show Offers');
        if (request()->ajax()) {
            $offers = $this->_model->with(['price', 'price.product'])->whereHas('price', function ($query) {
                $query->currentCountry(optional(getCurrentCountry())->id);
            });
            $search = request()->get('search', false);
            $offers = $offers->when($search, function ($query) use ($search) {
                $query->whereHas('price', function ($q) use ($search) {
                    $q->whereHas('product', function ($qq) use ($search) {
                        $qq->where('name->' . lang(), 'like', '%' . $search . '%');
                    });
                });
            });
            return DataTables::make($offers)
                ->escapeColumns([])
                ->addColumn('image', function ($offer) {
                    $price = $offer->price;
                    $product = optional($price)->product;
                    return '<img src="' . asset(optional($product)->images()->first()->image) . '" width="100" />';
                })
                ->addColumn('name', function ($offer) {
                    $price = $offer->price;
                    $product = optional($price)->product;
                    return optional($product)->name;
                })
                ->addColumn('piece_cost', function ($offer) {
                    $price = $offer->price;
                    return optional($price)->piece_cost;
                })
                ->addColumn('offer_piece_cost', function ($offer) {
                    return optional($offer)->piece_cost;
                })
                ->addColumn('retailer_package_cost', function ($offer) {
                    return optional($offer)->retailer_package_cost;
                })
                ->addColumn('wholesaler_package_cost', function ($offer) {
                    return optional($offer)->wholesaler_package_cost;
                })
                ->addColumn('remaining', function ($offer) {
                    $price = $offer->price;
                    return optional($price)->package_number * optional($price)->piece_per_package_number;
                })
                ->addColumn('status_offer', function ($offer) {
                    return optional($offer)->status_name;
                })
                ->addColumn('actions', function ($offer) {
                    return $offer->action_buttons;
                })
                ->make();
        }
        return view('manager.offer.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Product');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $products = ProductPrices::with(['product'])->currentCountry(optional(getCurrentCountry())->id)->get()->pluck('product');
        return view('manager.offer.edit', compact('title', 'validator', 'products'));
    }


    public function edit($id)
    {
        $title = t('Edit Product');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $offer = $this->_model->findOrFail($id);
        $products = ProductPrices::with(['product'])->currentCountry(optional(getCurrentCountry())->id)->get()->pluck('product');
        return view('manager.offer.edit', compact('title', 'offer', 'validator', 'products'));
    }

    public function store(Request $request)
    {
        $store = isset($request->offer_id) ? $this->_model->findOrFail($request->offer_id) : new $this->_model();
        $request->validate($this->validationRules);
        if (!isset($request->offer_id)) $store->product_price_id = Product::with(['price'])->findOrFail($request->product)->price->id;
        $store->wholesaler_percent = $request->wholesaler_percent;
        $store->retailer_percent = $request->retailer_percent;
        $store->customer_percent = $request->customer_percent;
        $store->active = $request->get('active', false);
        $store->type = $request->offer_type;
        $store->day = $request->day;
        $store->from = $request->from;
        $store->to = $request->to;
        $store->save();
        if (isset($request->offer_id)) {
            return redirect()->route('manager.offer.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.offer.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function destroy($id)
    {
        $country = $this->_model->findOrFail($id);
        $country->delete();
        return redirect()->route('manager.offer.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
