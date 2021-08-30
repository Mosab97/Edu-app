<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Advantage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class AdvantageController extends Controller
{
    private $_model;
    private $view = Advantage::manager_route;

    public function __construct(Advantage $advantage)
    {
        parent::__construct();
        $this->_model = $advantage;
        $this->middleware('permission:Advantages', ['only' => ['index', 'create', 'edit']]);
    }

    public function index()
    {
        $title = t('Show Advantages');
        if (request()->ajax()) {
            $advantages = $this->_model->query();
            $search = request()->get('search', false);
            return DataTables::make($advantages)
                ->escapeColumns([])
                ->addColumn('created_at', function ($advantage) {
                    return Carbon::parse($advantage->created_at)->toDateTimeString();
                })
                ->addColumn('title', function ($advantage) {
                    return $advantage->title;
                })
                ->addColumn('details', function ($advantage) {
                    return $advantage->details;
                })
                ->addColumn('actions', function ($advantage) {
                    return $advantage->action_buttons;
                })
                ->make();
        }
        return view($this->view . 'index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Advantage');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view($this->view . 'edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $store = isset($request->advantage_id) ? $this->_model->findOrFail($request->advantage_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->title = $request->title;
        $store->details = $request->details;
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->file('image'), 'Advantages');
        $store->save();
        $message = isset($request->advantage_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route(Advantage::manager_route . 'index')->with('m-class', 'success')->with('message', $message);
    }



    public function edit($id)
    {
        $title = t('Edit Advantage');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $advantage = $this->_model->query()->findOrFail($id);
        return view($this->view . 'edit', compact('title', 'advantage', 'validator'));
    }


    public function destroy($id)
    {
        $advantage = $this->_model->query()->findOrFail($id);
        $advantage->delete();
        return redirect()->route(Advantage::manager_route . 'index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
