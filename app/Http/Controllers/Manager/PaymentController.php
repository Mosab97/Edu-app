<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    private $_model;
    private $view = 'manager.' . Payment::manager_route . '.';

    public function __construct(Payment $payment)
    {
        parent::__construct();
        $this->_model = $payment;
//        $this->middleware('permission:Payments', ['only' => ['index', 'create', 'edit']]);
    }

    public function index()
    {
        $title = t('Show Blogs');
        if (request()->ajax()) {
            $blogs = $this->_model->query();
            $search = request()->get('search', false);
            return DataTables::make($blogs)
                ->escapeColumns([])
                ->addColumn('created_at', function ($blog) {
                    return Carbon::parse($blog->created_at)->toDateTimeString();
                })
                ->addColumn('user_id', function ($blog) {
                    return optional($blog->user)->id;
                })
                ->addColumn('user', function ($blog) {
                    return optional($blog->user)->name;
                })
                ->addColumn('actions', function ($blog) {
//                    return $blog->action_buttons;
                })
                ->make();
        }
//        dd($this->view . 'index');
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
