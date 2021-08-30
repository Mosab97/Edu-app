<?php


namespace App\Http\Controllers\Web\paypal;


use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\Order;
use App\Models\Service;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalServicesController extends Controller
{

    private $api_context;
    private $secret;
    private $clientId;
    private $currency = 'USD';
    private $amount = 0.01;

    public function __construct()
    {

        $paypal_config = Config::get('paypal');

        if ($paypal_config['settings']['mode'] == 'sandbox') {
            $this->clientId = $paypal_config['sandbox_client_id'];
            $this->secret = $paypal_config['sandbox_secret'];
        } else {
            $this->clientId = $paypal_config['live_client_id'];
            $this->secret = $paypal_config['live_secret'];
        }
        $this->api_context = new ApiContext(new OAuthTokenCredential($this->clientId, $this->secret));
        $this->api_context->setConfig($paypal_config['settings']);
    }

    public function index()
    {
        return view('payment.index');
    }

    private function getRules($service_id)
    {
        $serviceValidationRules = [];
        switch ($service_id) {
            case Service::f3Types['Help in choosing the accounting program']:
                $serviceValidationRules['project_activity'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['company_offers'] = ['required', 'numeric'];
                $serviceValidationRules['profile'] = ['nullable', 'url'];
                $serviceValidationRules['employee_number'] = ['required', 'numeric'];
                $serviceValidationRules['size_of_the_estimated_revenue_of_the_project'] = ['required', 'numeric'];
                $serviceValidationRules['size_of_the_estimated_expenses_of_the_project'] = ['required', 'numeric'];
                $serviceValidationRules['monthly_budget_allocated_to_the_accounting_program'] = ['required', 'numeric'];
                $serviceValidationRules['lang'] = ['required', 'numeric'];
                $serviceValidationRules['details'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['describe_your_need_for_the_program'] = ['required', 'min:3', 'max:255'];
                break;
            case Service::f3Types['Establishing the chart of accounts']:
                $serviceValidationRules['work_activity'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['current_accounting_program'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['mechanism_of_action'] = ['required', 'numeric'];
                $serviceValidationRules['service_description'] = ['required', 'min:3', 'max:255'];
                break;
            case Service::f3Types['Training service']:
                $serviceValidationRules['training_title'] = ['required', 'min:3', 'max:255'];
                $serviceValidationRules['mechanism_of_action'] = ['required', 'numeric'];
                $serviceValidationRules['number_people'] = ['required', 'numeric'];
                $serviceValidationRules['number_of_hours_required'] = ['required', 'numeric'];
                $serviceValidationRules['training_requirements'] = ['required', 'min:3', 'max:255'];
                break;
            default:
                $v = 'def_service_form';
                break;
        }
        return $serviceValidationRules;

    }

    public function beforePayment(Request $request)
    {
        $request->validate($this->getRules($request->service_id));
        $user = user('web');
        $data = $request->except(['_token', 'other_help_attachments']);
        $service = Service::findOrFail($request->service_id);
        if ($service->id == Service::f3Types['Training service']) {
            $service_price = $service->hour_price * $request->number_of_hours_required;
            $commission_active = setting('commission_active');
            $commission = $commission_active ? setting('commission') : 0;
            $commission_cost = $commission_active ? ($service_price * ($commission / 100)) : 0;
            $data['total_price'] = $service_price + $commission_cost;
        }
//        dd($data);
//        if (isset($user) && $user->orders()->where('service_id', $service->id)->count() > 0) return redirect()->back()->with('m-class', 'success')->with('message', w('Can not Register more than once'));
        $data['user_id'] = $user->id;

        $order = Order::create($data);
        Notification::send(Manager::get(), new NewOrderNotification($order));
        return view('website.service.bill', compact('service', 'order'));
        dd(checkRequestIsWorkingOrNot());
    }

    public function cancelBeforePayment(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->delete();
        return redirect()->route('home');
    }

    public function payment(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
//        dd($order_id,$order);
        $service = Service::findOrFail($request->service_id);
        $this->amount = $order->total_price;
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName(w('Pay for service', ['service' => $service->name]))
            ->setCurrency($this->currency)
            ->setQuantity(1)
            ->setDescription(w('Pay for service to the site Injaz', ['service' => $service->name]))
            ->setPrice($this->amount);
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency($this->currency)->setTotal($this->amount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($item_list)->setDescription('Your transaction description');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('payment.paypal.status'))->setCancelUrl(route('payment.paypal.cancel'));
        $payment = new Payment();
        $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions(array($transaction));
        try {
            $payment->create($this->api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return redirect()->route('home')->with('m-class', 'error')->with('message', t('Cancel Payment'));

            dd($ex->getData(), 234234);
//            flash_error('Payment failed');
            $url = route('site.payment.index', $course->id);
            return response()->json(compact(
                'url'
            ));
//            return redirect()->route('site.payment.index');
        }
        $paymentLink = $payment->getApprovalLink();
        return redirect($paymentLink);
    }

    public function status(Request $request)
    {
        if (empty($request->PayerID) || empty($request->token)) {
            return redirect()->route('home')->with('m-class', 'error')->with('message', t('Cancel Payment'));

            flash_error('Payment failed');
        } else {
            $payment = Payment::get($request->paymentId, $this->api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);
            $result = $payment->execute($execution, $this->api_context);
            if ($result->getState() == 'approved') {
                $paymentDB = \App\Models\Payment::create([
                    'user_id' => user('web')->id,
                    'currency' => $this->currency,
                    'amount' => $this->amount,
                    'payer_id' => $request->PayerID,
                    'payment_id' => $request->paymentId
                ]);

//                if ($paymentDB) {
//                    Membership::create([
//                        'payment_id' => $paymentDB->id,
//                        'user_id' => user_site()->id
//                    ]);
//                }

//                dd('Thank you for paying. Your membership will be reviewed by the administration');

            } else {
                dd('Payment failed');
            }
        }
//        dd('test');
        return redirect()->route('home')->with('m-class', 'success')->with('message', w('Thanks the specialist for contacting you for a period of no more than 24 hours.'));
        return redirect()->route('payment.index')->with('m-class', 'success')->with('message', w('Thanks the specialist for contacting you for a period of no more than 24 hours.'));

//        return ->with('');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('m-class', 'error')->with('message', t('Cancel Payment'));

        flash_error('Payment failed');
        return redirect()->route('site.payment.index');
    }
}
