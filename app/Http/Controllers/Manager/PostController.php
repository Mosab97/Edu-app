<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AdImages;
use App\Models\Merchant;
use App\Models\Post;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    private $_model;

    public function __construct(Post $post)
    {
        parent::__construct();
        $this->_model = $post;
//        $this->middleware('permission:Post', ['only' => ['index', 'create', 'edit', 'destroy']]);
        $this->validationRules["image"] = 'required|image';
        foreach (config('translatable.locales') as $local) $this->validationRules["title.$local"] = 'required|string|min:3|max:100';
        foreach (config('translatable.locales') as $local) $this->validationRules["details.$local"] = 'required|string|min:3|max:300';
    }

    public function index()
    {
        $title = t('Show Posts');
        if (request()->ajax()) {
            $posts = $this->_model->latest('updated_at');
            return DataTables::make($posts)
                ->escapeColumns([])
                ->addColumn('created_at', function ($post) {
                    return Carbon::parse($post->created_at)->toDateTimeString();
                })
                ->addColumn('image', function ($post) {
                    return '<img src="' . asset($post->image) . '" width="50" height="50" />';
                })
                ->addColumn('title', function ($post) {
                    return $post->title;
                })
                ->addColumn('actions', function ($post) {
                    return $post->action_buttons;
                })
                ->make();
        }
        return view('manager.post.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Slider');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.post.edit', compact('title', 'validator'));
    }

    public function edit($id)
    {
        $title = t('Edit Slider');
        $this->validationRules["image"] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $post = $this->_model->findOrFail($id);
        return view('manager.post.edit', compact('title', 'post', 'validator'));
    }

    public function store(Request $request)
    {
        if (isset($request->post_id)) {
            $this->validationRules["image"] = 'nullable|image';
            $store = $this->_model->findOrFail($request->post_id);
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->file('image'), 'posts');
        $store->title = $request->title;
        $store->details = $request->details;
        $store->save();
        if (isset($request->slider_id)) {
            return redirect()->route('manager.post.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.post.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function destroy($id)
    {
        $post = $this->_model->findOrFail($id);
        $post->delete();
        return redirect()->route('manager.post.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
