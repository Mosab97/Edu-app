<?php

namespace App\Http\Controllers\Manager;

use App\Events\AddNewBalanceEvent;
use App\Events\UserNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    private $_model;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->_model = $user;
//        $this->middleware('permission:Customers', ['only' => ['index', 'create', 'edit', 'show', 'destroy']]);

        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,{$id},id,NULL'];
//        $this->validationRules["password"] = password_rules(true, 6);

        $this->validationRules["city_id"] = 'required|exists:cities,id';
        $this->validationRules["image"] = 'nullable|image';
    }

    public function index()
    {
        $title = t('Show Users');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);
            $mobile = request()->get('mobile', false);
            $items = $this->_model->customer()
                ->searchName($name)
                ->searchMobile($mobile)
                ->when($status != null, function ($query) use ($status) {
                    $query->where('isBlocked', $status);
                })
                ->currentCountry(getCurrentCountry()->id);

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('status_name', function ($item) {
                    return $item->status_name;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        return view('manager.user.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Client');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.user.edit', compact('title', 'validator'));
    }

    public function edit($id)
    {
        $title = t('Edit Client');
        $client = $this->_model->customer()->findOrFail($id);
        return view('manager.user.edit', compact('title', 'client'));
    }


    public function store(Request $request)
    {
        $store = $this->_model->customer()->findOrFail($request->client_id);
        $store->isBlocked = !(boolean)$request->get('active', 0);
//        if (isset($request->active)) Notification::send($store, new NewOrderNotification($order));

        $store->save();
        return redirect()->route('manager.user.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
    }


    public function show($id)
    {
        $title = t('Show Client');
        $user = $this->_model->customer()->findOrFail($id);
        $data['points'] = $user->points;
        $data['orders_count'] = $user->orders()->completedOrder()->count();

        $this->validationRules['amount'] = 'required|gt:0';
        $this->validationRules['amount'] = 'required|gt:0';
        $validator = JsValidator::make($this->validationRules);

        $this->validationRules["title"] = 'required';
        $this->validationRules["content"] = 'required';
        $this->validationRules["user_id"] = 'required';
        $notify_validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('manager.user.show', compact('user', 'title', 'data', 'notify_validator', 'validator'));
    }


    public function destroy($id)
    {
        $item = $this->_model->customer()->findOrFail($id);
        if ($item->orders()->count() > 0) return redirect()->back()->with('message', t('Can not Delete Client, Client Related With Orders'))->with('m-class', 'error');
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


    public function userWallet($id)
    {
        $users = Wallet::query()->where('user_id', $id)->latest();
        $search = request()->get('search', false);
        return DataTables::make($users)
            ->escapeColumns([])
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->toDateTimeString();
            })
            ->addColumn('actions', function ($row) {
                return $row->action_buttons;
            })
            ->addColumn('uuid', function ($row) {
                return optional($row->order)->uuid;
            })
            ->addColumn('type_name', function ($row) {
                return $row->type_name;
            })
            ->make();
    }

    public function addWalletTransaction(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|gt:0',
            'note' => 'nullable',
        ]);
        $user = User::query()->findOrFail($id);
        $wallet = Wallet::query()->create([
            'user_id' => $id,
            'order_id' => null,
            'amount' => abs($request->get('amount')),
            'note' => $request->get('note', null),
            't_type' => '1',
        ]);
        event(new AddNewBalanceEvent($user, $wallet));

        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Added To Wallet'));
    }

    public function deleteWalletTransaction($id)
    {
        $wallet = Wallet::query()->findOrFail($id);
        $wallet->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'user_id' => 'required',
        ]);

        $user = User::query()->find($request->get('user_id'));
        if ($user) {
            event(new UserNotificationEvent($user, $request->get('title'), $request->get('content')));
            return redirect()->back()->with('m-class', 'success')->with('message', t('Notification Successfully Sent'));
        }
        return redirect()->back()->with('m-class', 'error')->with('message', t('Branch Not Found'));

    }

    public function notifications()
    {
        $title = t('Show Users');
        if (request()->ajax()) {

            $user = request()->get('user', false);

            $notifications = Notification::query()->when($user, function ($query) use ($user) {
                $query->where('notifiable_id', $user)->orWhere('notifiable_id', 0);
            });

            return DataTables::make($notifications)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('content', function ($row) {
                    return $row->body;
                })
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        //return view('manager.notification.index', compact('title'));
    }

}
