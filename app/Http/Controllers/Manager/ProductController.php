<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DistributorProducts;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductPrices;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    private $_model;

    public function __construct(ProductPrices $productPrices)
    {
        parent::__construct();
        $this->_model = $productPrices;
//        $this->middleware('permission:products', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) $this->validationRules["name.$local"] = 'required|max:100';
        foreach (config('translatable.locales') as $local) $this->validationRules["description.$local"] = 'required';
        $this->validationRules["images"] = 'required|array';
        $this->validationRules["category_id"] = 'required|exists:categories,id';
        $this->validationRules["piece_per_package"] = 'required|numeric|min:1|max:100000';
        $this->validationRules["quantity"] = 'required|numeric|min:1|max:100000';
        $this->validationRules["piece_cost"] = 'required|numeric|min:1|max:100000';
        $this->validationRules["wholesaler_package_cost"] = 'required|numeric|min:1|max:100000';
        $this->validationRules["retailer_package_cost"] = 'required|numeric|min:1|max:100000';
    }


    public function index()
    {
        $title = t('Show Products');
        if (request()->ajax()) {
            $products = $this->_model->with(['product'])->currentCountry(getCurrentCountry()->id);
            $search = request()->get('search', false);
            $products = $products->when($search, function ($query) use ($search) {
                $query->whereHas('product', function ($qq) use ($search) {
                    $qq->where('name->' . lang(), 'like', '%' . $search . '%');
                });
            });
            return DataTables::make($products)
                ->escapeColumns([])
                ->addColumn('image', function ($product_price) {
                    return '<img src="' . asset(optional(optional(optional($product_price)->product)->images()->first())->image) . '" width="100" />';
                })
                ->addColumn('name', function ($product_price) {
                    $name = optional(optional($product_price)->product)->name;

                    return strlen($name) > 70 ? substr(optional($product_price)->name, 0, 70) . '....' : $name;
                })
                ->addColumn('category', function ($product_price) {
                    return optional(optional(optional($product_price)->product)->category)->name;
                })
                ->addColumn('piece_cost', function ($product_price) {
                    return (float)number_format(optional($product_price)->piece_cost, DECIMAL_DIGIT_NUMBER, DECIMAL_SEPARATOR, DIGIT_THOUSANDS_SEPARATOR);
                })
                ->addColumn('piece_retailer_wholesaler', function ($product_price) {
                    $retailer_package_cost = (float)number_format(optional($product_price)->retailer_package_cost, DECIMAL_DIGIT_NUMBER, DECIMAL_SEPARATOR, DIGIT_THOUSANDS_SEPARATOR);
                    $wholesaler_package_cost = (float)number_format(optional($product_price)->wholesaler_package_cost, DECIMAL_DIGIT_NUMBER, DECIMAL_SEPARATOR, DIGIT_THOUSANDS_SEPARATOR);
                    return $retailer_package_cost . ' - ' . $wholesaler_package_cost;
                })
                ->addColumn('quantity', function ($product_price) {
                    return optional($product_price)->quantity;
                })
                ->addColumn('created_at', function ($product_price) {
                    return Carbon::parse($product_price->product->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($product_price) {
                    return $product_price->action_buttons;
                })
                ->make();
        }
        return view('manager.product.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Product');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $categories = Category::get();
        return view('manager.product.edit', compact('title', 'validator', 'categories'));
    }

    public function show($id)
    {
        $title = t('Show Product');
        $product_price = $this->_model->with(['product'])->findOrFail($id);
        $product = $product_price->product;
        $title = t('Show Client');
//        $user = $this->_model->client()->findOrFail($id);
        $data['wallet_balance'] = 0;//$user->user_wallet;
        $data['orders_count'] = 0;
        $data['addresses_count'] = 0;
        $data['rates_count'] = 0;
        $distributors = optional(getCurrentCountry())->distributors;

        $this->validationRules['amount'] = 'required|gt:0';
        $this->validationRules['distributor'] = 'required|exists:users,id';
        $distributor_validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.product.show', compact('title', 'data', 'product_price','product', 'distributor_validator', 'distributors'));
    }

    public function edit($id)
    {
        $this->validationRules["images"] = 'nullable|array';

        $title = t('Edit Product');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $product_price = $this->_model->with(['product'])->findOrFail($id);
        $categories = Category::get();
        return view('manager.product.edit', compact('title', 'product_price', 'categories', 'validator'));
    }

    private function addProduct($request, $store, $price)
    {
        $store->name = $request->name;
        $store->description = $request->description;
        $store->category_id = $request->category_id;
        $store->save();
//        create price for this product

        if (!isset($price)) $price = new ProductPrices();

        $price->country_id = getCurrentCountry()->id;
        $price->product_id = $store->id;
        $price->wholesaler_package_cost = $request->wholesaler_package_cost;
        $price->retailer_package_cost = $request->retailer_package_cost;
        $price->piece_cost = $request->piece_cost;
//        $price->package_number = $request->total_number_of_pieces / $request->piece_per_package_number;
        $price->piece_per_package = $request->piece_per_package;
        $price->quantity = $request->quantity;
        $price->remaining = $request->quantity;
        $price->save();

        return $price;
    }

    private function addProductImages($request, $product_price)
    {
        if (isset($request->images) && is_array($request->images)) {
            if ($request->product_id) $product_price->product->customer_images()->delete();
            foreach ($request->images as $index => $image) $product_price->product->customer_images()->create([
                'image' => $this->uploadImage($image, 'products'),
                'type' => ProductImages::type['CUSTOMER'],
            ]);
        }
        if (isset($request->merchant_images) && is_array($request->merchant_images)) {
            if ($request->product_id) $product_price->product->merchant_images()->delete();
            foreach ($request->merchant_images as $index => $image) {
                $product_price->product->merchant_images()->create([
                    'image' => $this->uploadImage($image, 'products'),
                    'type' => ProductImages::type['MERCHANT'],
                ]);
            }
        }

    }

    public function store(Request $request)
    {
        if (isset($request->product_id)) {
            $this->validationRules["images"] = 'nullable|array';
            $store = Product::findOrFail($request->product_id);
            $price = $store->price;
        } else {
            $store = new Product();
            $price = new ProductPrices();
        }
        $request->validate($this->validationRules);
//        dd(checkRequestIsWorkingOrNot());
        $product_price = $this->addProduct($request, $store, $price);
        $this->addProductImages($request, $product_price);
        return (isset($request->product_id)) ? redirect()->route('manager.product.index')->with('m-class', 'success')->with('message', t('Successfully Updated')) : redirect()->route('manager.product.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
    }


    public function destroy($id)
    {
        $product_price = $this->_model->findOrFail($id);
//        if ($product_price->product->prices()->count()) return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete Product it has prices'));
        $product_price->delete();
        return redirect()->route('manager.product.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }

    public function product_distributors(Request $request, $price_id)
    {
        if (request()->ajax()) {
            $distributors = DistributorProducts::with(['distributor'])->where(['price_id' => $price_id,])->get();
            return DataTables::make($distributors)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return optional(optional($item)->distributor)->name;
                })
                ->addColumn('amount', function ($item) {
                    return optional($item)->amount;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->make();
        }
    }

    public function update_amount(Request $request, $product_distributor_id)
    {
        $request->validate(['amount' => 'required|gt:0']);
        $product_distributor = DistributorProducts::findOrFail($product_distributor_id);
        $product_distributor->update([
            'amount' => $request->amount
        ]);
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Changed'));

    }

    public function deleteDistributorProduct(Request $request, $id)
    {
        $product_distributor = DistributorProducts::findOrFail($id);
        $product_distributor->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));

    }

    public function product_images(Request $request, $id)
    {
        if (request()->ajax()) {
            $product_images = ProductImages::where('product_id', $id);
            return DataTables::make($product_images)
                ->escapeColumns([])
                ->addColumn('image', function ($image) {
                    return '<a href="' . asset($image->image) . '" target="_blank"><img  src="' . asset($image->image) . '" width="100" /></a>';
                })
                ->addColumn('created_at', function ($image) {
                    return Carbon::parse($image->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($image) {
                    return $image->action_buttons;
                })
                ->make();
        }
    }

}
