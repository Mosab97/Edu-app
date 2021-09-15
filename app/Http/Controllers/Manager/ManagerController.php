<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class ManagerController extends Controller
{
    private $_model;

    public function __construct(Manager $manager)
    {
        parent::__construct();
        $this->_model = $manager;
//        $this->middleware('permission:map', ['only' => ['index', 'create', 'edit']]);
        $this->validationRules = [
            'name' => 'required',
            'password' => 'required',
            'country_id' => 'required|exists:countries,id',
            'email' => 'required|email|unique:managers,email',
        ];
    }

    public function index()
    {
        if (request()->ajax()) {
            $managers = $this->_model->query()->where('id', '<>', 1)
                ->where('country_id', optional(getCurrentCountry())->id)
                ->where('id', '<>', optional(user('manager'))->id)->latest();

            return DataTables::make($managers)
                ->escapeColumns([])
                ->addColumn('created_at', function ($manager) {
                    return Carbon::parse($manager->created_at)->toDateString();
                })
                ->addColumn('actions', function ($manager) {
                    return $manager->action_buttons;
                })
                ->make();
        }

        $title = t('Show Managers Accounts List');
        return view('manager.manager.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Manager');
        $roles = Role::query()->where('guard_name', 'manager')->get();
        $userRole = array();
        $countries = Country::get();
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.manager.edit', compact('title', 'roles', 'userRole', 'validator', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:managers,email',
            'password' => 'required|min:6',
        ]);
        $data = $request->except(['roles']);
        $data['password'] = bcrypt($request->get('password'));
        $manager = Manager::create($data);
        if ($request->has('roles') && count($request->get('roles')) > 0)
            $manager->assignRole($request->input('roles'));


        return redirect()->route('manager.manager.index')->with('m-class', 'success')->with('message', t('Successfully Added'));
    }

    public function edit($id)
    {
        $title = t('Edit Manager');
        $manager = Manager::query()->findOrFail($id);
        $roles = Role::query()->where('guard_name', 'manager')->get();
        $userRole = optional(optional($manager)->roles)->pluck('id')->all();
        $this->validationRules['password'] = 'nullable';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $countries = Country::get();
        return view('manager.manager.edit', compact('title', 'manager', 'roles', 'userRole', 'validator', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $manager = Manager::query()->findOrFail($id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:managers,email,' . $id,
        ]);

        $data = $request->except(['roles']);
        if ($request->has('password') && !is_null($request->get('password'))) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = bcrypt($request->get('password'));
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($request->get('password'));
        } else {
            $data = Arr::except($data, array('password'));
        }
        $manager->update($data);
        DB::table('model_has_roles')->where('model_id', $manager->id)->delete();
        if ($request->has('roles') && count($request->get('roles')) > 0)
            $manager->assignRole($request->input('roles'));


        return redirect()->route('manager.manager.index')->with('message', t('Successfully Updated'));
    }

    public function destroy($id)
    {
        $manager = Manager::query()->findOrFail($id);
        if (Auth::guard('manager')->user()->id == $id) {
            return back()->with('m-class', 'error')->with('message', t('You cannot delete yourself from the system'));
        }
        $manager->delete();
        return redirect()->route('manager.manager.index')->with('message', t('Successfully Deleted'));
    }

}
