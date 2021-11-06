<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\StudentGroups;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    private $_model;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->_model = $user;
    }

    public function index()
    {
        $title = t('Show Students');
        if (request()->ajax()) {
            $search = request()->get('search', false);
            $items = $this->_model->query()->when($search, function ($query) use ($search) {
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
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->make();
        }
        return view('manager.' . User::manager_route_user_type['student'] . '.index', compact('title'));
    }

    public function get_courses(Request $request)
    {
        if (request()->ajax()) {
            $student = request()->get('student', false);
            $items = StudentGroups::query()->when($student, function ($query) use ($student) {
                $query->where('student_id', $student);
            });
            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->addColumn('group_name', function ($item) {
                    return $item->group->name;
                })
                ->addColumn('course_name', function ($item) {
                    return $item->course->name;
                })
                ->addColumn('is_paid', function ($item) {
                    return $item->is_paid ? t('Paid') : t('Not Paid');
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->make();
        }
    }

    public function show(Request $request, $id)
    {
        $title = t('Show ' . User::manager_route_user_type['student']);
        $user = $this->_model->findOrFail($id);
        return view('manager.' . User::manager_route_user_type['student'] . '.show', compact('user', 'title'));
    }

    public function create()
    {
        $title = t('Add Course');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.' . Course::manager_route . '.edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $store = isset($request->age_id) ? $this->_model->findOrFail($request->age_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->save();
        $message = isset($request->age_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route('manager.' . Course::manager_route . '.index')->with('m-class', 'success')->with('message', $message);
    }


    public function edit($id)
    {
        $title = t('Edit Course');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $age = $this->_model->query()->findOrFail($id);
        return view('manager.' . Course::manager_route . '.edit', compact('title', 'age', 'validator'));
    }


    public function destroy($id)
    {
        $item = $this->_model->query()->findOrFail($id);
        $item->delete();
        return redirect()->route('manager.' . Course::manager_route . '.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
