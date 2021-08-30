<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CustomerReviews;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class BlogController extends Controller
{
    private $_model;
    private $view = 'manager.blogs.';

    public function __construct(Blog $blog)
    {
        parent::__construct();
        $this->_model = $blog;
        $this->middleware('permission:Blogs', ['only' => ['index', 'create', 'edit']]);
    }

    public function index()
    {
        $title = t('Show Blogs');
        if (request()->ajax()) {
            $blogs = $this->_model->query();
            $search = request()->get('search', false);
//            $blogs = $blogs->when($search, function ($query) use ($search) {
//                $query->
////                where('rate', $search )
////                    ->or
//                where('title->' . lang(), 'like', '%' . $search . '%')
//                    ->orWhere('details->' . lang(), 'like', '%' . $search . '%')
//                    ->orWhere('customer_name->' . lang(), 'like', '%' . $search . '%');
//            });
            return DataTables::make($blogs)
                ->escapeColumns([])
                ->addColumn('created_at', function ($blog) {
                    return Carbon::parse($blog->created_at)->toDateTimeString();
                })
                ->addColumn('title', function ($blog) {
                    return $blog->title;
                })
                ->addColumn('short_details', function ($blog) {
                    return $blog->short_details;
                })
                ->addColumn('actions', function ($blog) {
                    return $blog->action_buttons;
                })
                ->make();
        }
        return view($this->view . 'index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Blog');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view($this->view . 'edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $store = isset($request->blog_id) ? $this->_model->findOrFail($request->blog_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->title = $request->title;
        $store->details = $request->details;
        $store->short_details = $request->short_details;
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->file('image'), 'Blogs');
        $store->save();
        $message = isset($request->blog_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route(Blog::manager_route . 'index')->with('m-class', 'success')->with('message', $message);
    }


    public function edit($id)
    {
        $title = t('Edit Blog');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $blog = $this->_model->query()->findOrFail($id);
        return view($this->view . 'edit', compact('title', 'blog', 'validator'));
    }


    public function destroy($id)
    {
        $blog = $this->_model->query()->findOrFail($id);
        $blog->delete();
        return redirect()->route(Blog::manager_route . 'index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
