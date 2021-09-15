<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    private $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
//        $this->middleware('permission:Categories', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) $this->validationRules["name.$local"] = 'required';
    }

    public function index()
    {
        $title = t('Show Categories');
        if (request()->ajax()) {
            $categories = $this->model;
            $search = request()->get('search', false);
            $categories = $categories->when($search, function ($query) use ($search) {
                $query->where('name->' . lang(), 'like', '%' . $search . '%');
            });
            return DataTables::make($categories)
                ->escapeColumns([])
                ->addColumn('created_at', function ($category) {
                    return Carbon::parse($category->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($category) {
                    return $category->action_buttons;
                })
                ->addColumn('name', function ($category) {
                    return $category->name;
                })
                ->addColumn('image', function ($category) {
                    return '<img src="' . asset($category->image) . '" width="100" />';
                })
                ->make();
        }
        return view('manager.category.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Category');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.category.edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules);
        $store = isset($request->category_id) ? $this->model->findOrFail($request->category_id) : new $this->model();
        $store->name = $request->name;
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->file('image'), 'categories');
        $store->draft = $request->get('draft', 0);
        $store->save();
        return isset($request->merchant_type_id) ? redirect()->route('manager.category.index')->with('m-class', 'success')->with('message', t('Successfully Updated')) : redirect()->route('manager.category.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
    }

    public function edit($id)
    {
        $title = t('Edit Category');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $category = Category::query()->findOrFail($id);
        return view('manager.category.edit', compact('title', 'category', 'validator'));
    }

    public function destroy($id)
    {
        $category = $this->model->findOrFail($id);
        if ($category->products()->count()) return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete category it has products'));
        $category->delete();
        return redirect()->route('manager.category.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
