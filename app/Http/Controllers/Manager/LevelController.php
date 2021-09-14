<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class LevelController extends Controller
{
    private $_model;

    public function __construct(Level $level)
    {
        parent::__construct();
        $this->_model = $level;
//        $this->middleware('permission:Levels', ['only' => ['index', 'create', 'edit']]);
//        foreach (config('translatable.locales') as $local)
//        {
//            $this->validationRules["name.$local"] = 'required';
//        }
//        $this->validationRules["ordered"] = 'required';
    }

    public function index()
    {
        $title = t('Show Levels');
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
        return view('manager.' . Level::manager_route . '.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Level');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.' . Level::manager_route . '.edit', compact('title', 'validator'));
    }

    public function store(Request $request)
    {
        $store = isset($request->level_id) ? $this->_model->findOrFail($request->level_id) : new $this->_model();
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->save();
        $message = isset($request->level_id) ? t('Successfully Updated') : t('Successfully Created');
        return redirect()->route('manager.' . Level::manager_route . '.index')->with('m-class', 'success')->with('message', $message);
    }


    public function edit($id)
    {
        $title = t('Edit Level');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $level = $this->_model->query()->findOrFail($id);
        return view('manager.' . Level::manager_route . '.edit', compact('title', 'level', 'validator'));
    }


    public function destroy($id)
    {
        $item = $this->_model->query()->findOrFail($id);
        $item->delete();
        return redirect()->route('manager.' . Level::manager_route . '.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
