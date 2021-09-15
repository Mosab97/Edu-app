<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductPrices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class ProductImagesController extends Controller
{
    private $_model;

    public function __construct(ProductImages $productImages)
    {
        parent::__construct();
        $this->_model = $productImages;
        $this->validationRules["image"] = 'nullable|image';
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules);
        $product = Product::findOrFail($request->product_id);
        $product->images()->create([
            'image' => $this->uploadImage($request->image, 'products')
        ]);
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Created'));
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->validationRules);
        $image = ProductImages::findOrFail($id);
        $image->update([
            'image' => $this->uploadImage($request->image, 'products')
        ]);
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Created'));
    }

    public function destroy($id)
    {
        $product = $this->_model->findOrFail($id);
        $product->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
