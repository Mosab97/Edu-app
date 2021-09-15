<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\DistributorProducts;
use App\Models\Product;
use App\Models\ProductPrices;
use App\Models\User;
use App\Rules\IntroMobile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class DistributorController extends Controller
{
    private $_model;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->_model = $user;
//        $this->middleware('permission:Distributor', ['only' => ['index', 'create', 'edit', 'show', 'destroy']]);

        foreach (config('translatable.locales') as $local) {
            $this->validationRules["first_name.$local"] = 'required';
            $this->validationRules["last_name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'min:13'/*, 'max:13'*/, new IntroMobile(), 'unique:users,mobile,{$id},id'];
        $this->validationRules["password"] = password_rules(true, 6);
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["profit_percentage"] = 'required|numeric|min:1|max:100';
        $this->validationRules["stakeholder_type"] = 'required|in:' . implode(',', Distributor::stakeholders);
    }

    public function index()
    {
        $title = t('Show Distributors');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);
            $source = request()->get('source', false);
            $items = $this->_model->distributorUserType()
                ->searchName($name)
                ->currentCountry(getCurrentCountry()->id)
//                ->when($name, function ($query) use ($name) {
//                $query->where('first_name->' . lang(), 'like', '%' . $name . '%')
//                    ->orWhere('last_name->' . lang(), 'like', '%' . $name . '%');
//            })
                ->when($status != null, function ($query) use ($status) {
                    $query->where('isBlocked', $status);
                });

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('status_name', function ($item) {
                    return $item->status_name;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        return view('manager.distributor.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Distributor');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.distributor.edit', compact('title', 'validator'));
    }

    public function edit($id)
    {
        $title = t('Edit Client');
        $distributor = $this->_model->with(['distributor'])->distributorUserType()->findOrFail($id);
//        dd($distributor,$distributor->distributor);
        $this->validationRules["phone"] = ['required', 'min:13'/*, 'max:13'*/, new IntroMobile(), 'unique:users,mobile,' . $distributor->id . ',id'];
        $this->validationRules["password"] = 'nullable|min:6';
        $this->validationRules["image"] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.distributor.edit', compact('title', 'validator', 'distributor'));
    }


    public function store(Request $request)
    {
        if (isset($request->distributor_id)) {
            $store = $this->_model->distributorUserType()->findOrFail($request->distributor_id);
            $this->validationRules["phone"] = ['required', 'min:13'/*, 'max:13'*/, new IntroMobile(), 'unique:users,mobile,' . $store->id . ',id'];
            $this->validationRules["password"] = 'nullable|min:6';
            $this->validationRules["image"] = 'nullable|image';
//            $store->distributor()->update([
//                'profit_percentage' => $request->profit_percentage,
//                'receive_orders' => true,
//                'stakeholder_type' => $request->stakeholder_type,
//            ]);
        } else {
            $store = new $this->_model();
//            $store->distributor()->create([
//                'profit_percentage' => $request->profit_percentage,
//                'receive_orders' => true,
//                'stakeholder_type' => $request->stakeholder_type,
//            ]);
        }
        $request->validate($this->validationRules);
//        dd(checkRequestIsWorkingOrNot());
        $store->country_id = getCurrentCountry()->id;
        $store->first_name = $request->first_name;
        $store->last_name = $request->last_name;
        $store->mobile = $request->phone;
        $store->email = $request->email;
        if (isset($request->password)) $store->password = Hash::make($request->password);
        $store->user_type = User::DISTRIBUTOR;
        $store->isBlocked = !$request->get('active', 0);
        $store->verified = true;
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->file('image'), 'Distributors');
        $store->save();


        if (isset($request->distributor_id)) {
            $dist = $store->distributor;
        } else {
            $dist = new Distributor();
            $dist->user_id = $store->id;
        }
        $dist->profit_percentage = $request->profit_percentage;
        $dist->receive_orders = true;
        $dist->stakeholder_type = $request->stakeholder_type;
        $dist->save();
//        dd($dist);

        if (isset($request->distributor_id)) {
            return redirect()->route('manager.distributor.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.distributor.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function show($id)
    {
        $title = t('Show Client');
        $distributor = $this->_model->distributorUserType()->findOrFail($id);

        $this->validationRules['product_id'] = 'required|exists:products,id';
        $this->validationRules['amount'] = 'required|gt:0';
        $validator = JsValidator::make($this->validationRules);

        $this->validationRules["title"] = 'required';
        $this->validationRules["content"] = 'required';
        $this->validationRules["user_id"] = 'required';
        $notify_validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $products = ProductPrices::with(['product'])->where('country_id', getCurrentCountry()->id)->get();
        $orders = $distributor->orders()->count();
        return view('manager.distributor.show', compact('distributor', 'title', 'notify_validator', 'validator', 'products', 'orders'));
    }


    public function destroy($id)
    {
        $item = $this->_model->distributorUserType()->findOrFail($id);
        if ($item->distributor_orders()->count() > 0) return redirect()->back()->with('message', t('Can not Delete Client, Client Related With Orders'))->with('m-class', 'error');
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }

    public function add_products(Request $request, $distributor_id)
    {
        $request->validate(['product_id' => 'required|exists:product_prices,id', 'amount' => 'required|gte:0']);
        $price = ProductPrices::with(['product'])->currentCountry(getCurrentCountry()->id)->findOrFail($request->product_id);
        $remaining = optional($price)->quantity - optional($price)->number_of_reserved_pieces_from_distributors;
        if ($remaining < $request->amount) return redirect()->back()->with('m-class', 'error')->with('message', t('Amount must be smaller than ', ['remaining' => $remaining]));
        $distributor_product = DistributorProducts::where(['user_id' => $distributor_id, 'price_id' => $price->id,])->first();
        if (!isset($distributor_product)) $distributor_product = new DistributorProducts();
        $distributor_product->user_id = $distributor_id;
        $distributor_product->price_id = $request->product_id;
        $distributor_product->amount = $request->amount + $distributor_product->amount;
        $distributor_product->remaining = $distributor_product->amount;
//        dd($distributor_product);
        $distributor_product->save();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Assigned'));
    }

    public function assign_to_distributor(Request $request, $product_id)
    {
        $price = ProductPrices::where('product_id', $product_id)->with(['product'])->currentCountry(getCurrentCountry()->id)->first();
//        $product = optional($price)->product;
        $remaining = optional($price)->quantity - optional($price)->number_of_reserved_pieces_from_distributors;
        $request->validate([
            'amount' => 'required|gte:0|lte:' . $remaining,
            'distributor' => 'required|exists:users,id',
        ]);
        $distributor_products = DistributorProducts::where(['user_id' => $request->distributor, 'price_id' => $price->id,])->first();
        if (!isset($distributor_products)) $distributor_products = new DistributorProducts();
        $distributor_products->user_id = $request->distributor;
        $distributor_products->price_id = $price->id;
        $distributor_products->amount = $request->amount;
        $distributor_products->remaining = $request->amount;
        $distributor_products->save();
//        $price->remaining = $price->remaining - $request->amount;
        return redirect()->back()->with('m-class', 'success')->with('message', t('Product Assigned Successfully'));
    }

    public function destroy_distributor_products(Request $request, $id)
    {
        $item = DistributorProducts::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Item Deleted Successfully'));

    }

    public function distributor_products(Request $request, $distributor_id)
    {
        if (request()->ajax()) {
            $items = DistributorProducts::with(['price', 'price.product', 'distributor'])->currentDistributor($distributor_id);
            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('image', function ($item) {
                    $price = optional($item)->price;
                    $product = optional($price)->product;
                    return '<img src="' . asset(optional($product)->image) . '" alt="no image" width="100" />';
                })
                ->addColumn('name', function ($item) {
                    $price = optional($item)->price;
                    $product = optional($price)->product;
                    return optional($product)->name;
                })
                ->addColumn('price', function ($item) {
                    $price = optional($item)->price;
                    return optional($price)->piece_cost;
                })
                ->addColumn('amount', function ($item) {
                    return $item->amount;
                })
                ->addColumn('sold', function ($item) {
                    return $item->sold;
                })
                ->addColumn('remaining', function ($item) {
                    return $item->remaining;
                })
                ->addColumn('rate', function ($item) {
                    $price = optional($item)->price;
                    $product = optional($price)->product;
                    return optional($product)->rate;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
    }
}
