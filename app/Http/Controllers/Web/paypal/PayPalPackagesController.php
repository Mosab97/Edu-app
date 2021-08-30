<?php


namespace App\Http\Controllers\Web\paypal;


use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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

class PayPalPackagesController extends Controller
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

    public function beforePayment(Request $request, $package_id)
    {

        $user = user('web');
        $package = Package::findOrFail($package_id);
        $price = $package->price;
        $commission_active = setting('commission_active');
        $commission = $commission_active ? setting('commission') : 0;
        $commission_cost = $commission_active ? ($price * ($commission / 100)) : 0;
        $total_price = $price + $commission_cost;

        $user_package = $user->packages()->create([
            'package_id' => $package_id,
            'commission' => $commission,
            'commission_cost' => $commission_cost,
            'total_price' => $total_price,
        ]);

        return view('website.payment.package_bill', compact('package', 'user_package'));
        dd($package_id, checkRequestIsWorkingOrNot());
    }

    public function cancelBeforePayment(Request $request, $user_package)
    {
        $user_package = UserPackage::findOrFail($user_package);
        $user_package->delete();
        return redirect()->route('home');
    }

    public function payment(Request $request, $user_package, $package = null)
    {
        $user_package = UserPackage::findOrFail($user_package);
        $package = Package::findOrFail($package);

        $this->amount = $user_package->total_price;
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName(w('Pay for Package', ['package' => $package->name]))
            ->setCurrency($this->currency)
            ->setQuantity(1)
            ->setDescription(w('Pay for package to the site Injaz', ['package' => $package->name]))
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
            return redirect()->route('home')->with('m-class', 'error')->with('message', t('Payment failed'));
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
