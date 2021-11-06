<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class GroupController extends Controller
{
    private $_model;

    public function __construct(Group $group)
    {
        parent::__construct();
        $this->_model = $group;
    }

    public function index()
    {
        $title = t('Show ' . Group::ui['plural_name']);
        if (request()->ajax()) {
            $search = request()->get('search', false);
            if (is_array($search)) $search = false;
            $teacher = request()->get('teacher', false);
//            dd($search,$teacher);
            $items = $this->_model->query()
                ->when($teacher, function ($query) use ($teacher) {
                    $query->where('teacher_id', $teacher);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('course', function ($item) {
                    return $item->course->name;
                })
                ->addColumn('level', function ($item) {
                    return $item->level->name;
                })
                ->addColumn('age', function ($item) {
                    return $item->age->name;
                })
                ->addColumn('price', function ($item) {
                    return $item->price;
                })
                ->addColumn('gender', function ($item) {
                    return gender($item->gender);
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->make();
        }
        return view('manager.' . Group::ui['manager_route'] . '.index', compact('title'));
    }


    public function create()
    {
        $title = t('Add ' . Group::ui['single_name']);
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.' . Group::manager_route . '.edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $id = $request->get(Group::ui['update_form_hidden_field']);
        $store = $id ? $this->_model->findOrFail($id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->save();
        $message = $id ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route('manager.' . Group::manager_route . '.index')->with('m-class', 'success')->with('message', $message);
    }


    public function edit($id)
    {
        $title = t('Edit ' . Group::ui['single_name']);
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $item = $this->_model->query()->findOrFail($id);
        return view('manager.' . Group::manager_route . '.edit', compact('title', 'item', 'validator'));
    }


    public function destroy($id)
    {
        $item = $this->_model->query()->findOrFail($id);
        $item->delete();
        return redirect()->route('manager.' . Group::manager_route . '.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
