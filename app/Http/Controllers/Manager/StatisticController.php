<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class StatisticController extends Controller
{
    private $_model;
    private $view = Statistic::manager_route;

    public function __construct(Statistic $statistic)
    {
        parent::__construct();
        $this->_model = $statistic;
//        $this->middleware('permission:Statistics', ['only' => ['index', 'create', 'edit']]);
    }

    public function index()
    {
        $title = t('Show Statistics');
        if (request()->ajax()) {
            $statistics = $this->_model->query();
            return DataTables::make($statistics)
                ->escapeColumns([])
                ->addColumn('created_at', function ($statistic) {
                    return Carbon::parse($statistic->created_at)->toDateTimeString();
                })
                ->addColumn('key', function ($statistic) {
                    return $statistic->key;
                })
                ->addColumn('value', function ($statistic) {
                    return $statistic->value;
                })
                ->addColumn('actions', function ($statistic) {
                    return $statistic->action_buttons;
                })
                ->make();
        }
        return view($this->view . 'index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Statistic');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view($this->view . 'edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        try {

            $store = isset($request->statistic_id) ? $this->_model->findOrFail($request->statistic_id) : new $this->_model();
            $request->validate($this->validationRules);
            $store->key = $request->key;
            $store->value = $request->value;
            $store->save();
            $message = isset($request->statistic_id) ? t('Successfully Updated') : t('Successfully Created');
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
        return redirect()->route(Statistic::manager_route . 'index')->with('m-class', 'success')->with('message', $message);
    }


    public function edit($id)
    {
        $title = t('Edit Statistic');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $statistic = $this->_model->query()->findOrFail($id);
        return view($this->view . 'edit', compact('title', 'statistic', 'validator'));
    }


    public function destroy($id)
    {
        $statistic = $this->_model->query()->findOrFail($id);
        $statistic->delete();
        return redirect()->route(Statistic::manager_route . 'index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
