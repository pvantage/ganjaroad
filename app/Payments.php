<?php

namespace App;

use Log;
use Lang;
use Session;
use Sentinel;
use Exception;
use Carbon\Carbon;
use App\Helpers\Template;
use App\Services\PaymentService;
use Unirest\Request as UniRequest;
use App\Exceptions\PaymentException;
use App\Http\Requests\PaymentsRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Payments.
 *
 * @package App
 */
class Payments extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'payments';
    protected $guarded = ['id'];
    protected $transaction_type = 'authCaptureTransaction';

    private $expi_url = 'https://internationalaccessmedia.com/gr/gateway.php';
    private $headers = array();
    private $error_codes = array(1 => 'approved', 2 => 'declined', 3 => 'error');
    private $vault_code = 'recurring';
    private $vault_sale_code = 'recurring_sale';

    private function getAuthorizationKey()
    {
        return env('EXPI_LIB_API_KEY');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

	public function cart()
    {
        return $this->hasMany('App\Payments', 'cart_id');
    }

    public function items()
    {
        return $this->hasMany('App\PaymentItems', 'payment_id');
    }

    public function refunds()
    {
        return $this->hasMany('App\Refunds', 'payment_id');
    }

    public function setUpGateway()
    {
        //define("AUTHORIZENET_LOG_FILE", "phplog");
        $envirmoent = env('EXPI_ENVIROMENT');
        $merchant = new AnetAPI\MerchantAuthenticationType();
        $merchant->setName(env('EXPI_LOGIN_'.$envirmoent));
        $merchant->setTransactionKey(env('EXPI_PASS_'.$envirmoent));

        return $merchant;
    }

    public function setCreditCard(PaymentsRequest $request)
    {
        $credit_card = new AnetAPI\CreditCardType();
        $credit_card->setCardNumber($request->get('cc_number'));
        $credit_card->setExpirationDate($request->get('cc_date'));

        return $credit_card;
    }

    /**
     * Make payment.
     *
     * @param PaymentsRequest $cc
     * @param $total
     * @param $user_id
     * @param array $cartItems
     *
     * @return bool
     *
     * @throws Exception
     * @throws PaymentException
     */
    public function makePayment(PaymentsRequest $cc, $total, $user_id, $cartItems = [])
    {
        $user = Sentinel::findById($user_id);

        if (!$user) {
            throw new Exception(Lang::get('general.user_not_exist'));
        }

        $payment = new Payments();
        $payment->user_id = $user->id;
        $payment->amount = $total;
        $payment->tranasction_type = 'sale';
        $payment->save();

        if (Session::has('coupon_code')) {
            $payment->coupon = Session::get('coupon_code');
        }

        $desc = '';
        $discount = 0;

        if ($cartItems) {
            foreach ($cartItems as $item) {
                if ($item['qty'] && $item['qty'] > 1 ) {
                    $desc .= $item['qty'] . ' x ';
                }

                $desc .= $item['item_id'] . ' ';
                $discount += $item['discount'];

                PaymentItems::insertTransactionItem($payment, $item);

                $vaultItem = $this->checkForRecurring($item);

                if ($vaultItem) {
                    $this->setupVault($vaultItem, $cc, $user_id);
                }
            }
        }

        if ($discount) {
            $payment->discount = $discount;
            $payment->save();
        }

        $total = Cart::renderTotal(false);

        if ($total == 0) {
            $this->paymentSuccessResult($payment);

            return true;
        }

        $transactionType = env('EXPI_LIB_API_AUTHORIZATION_TYPE', 'sale');
        $expi = UniRequest::post(
            $this->expi_url,
            $this->headers,
            [
                'orderid' => $payment->id,
                'orderdescription' => $desc,
                'tax' => 0,
                'shipping' => 0,
                'ipaddress' => \Request::ip(),
                'ccnumber' => $cc->get('cc_number'),
                'ccexp' => $this->prepareCCDate($cc->get('cc_date')),
                'cvv' => $cc->get('cc_cvc'),
                'amount' => $total,
                'firstname' => $user->first_name,
                'lastname' => $user->last_name,
                'city' => $user->city,
                'zip' => $user->postal,
                'state' => $user->state,
                'country' => $user->country,
                'email' => $user->email,
                'autorization_key' => $this->getAuthorizationKey(),
                'type' => $transactionType,
            ]
        );

        if (!($expi->code == 200 && $expi->raw_body)) {
            throw new Exception(Lang::get('payments/message.error.could_not_get_response_from_payment_gateway'));
        }

        $jsonResponse = json_decode($expi->raw_body);

        if (!$jsonResponse->success) {
            throw new PaymentException($jsonResponse);
        }

        $payment->transaction_id = $jsonResponse->transactionid;
        $payment->paid = 1;
        $payment->tranasction_type = $transactionType;
        $payment->discount = Cart::getTotalDiscount(false);
        $payment->save();

        $this->paymentSuccessResult($payment);

        return true;
    }

    /**
     * Payment success result.
     *
     * @param Payments $payment
     */
    public function paymentSuccessResult(Payments $payment)
    {
        $ids = [];
        $items = Cart::getCart();

        foreach ($items as $cartItem) {
            $ids[$cartItem['item_id']] = $cartItem['item_id'];
        }

        $items_total = 0;
        $paymentItems = PaymentItems::where('payment_id', '=', $payment->id)->get();

        if ($paymentItems) {
            foreach ($paymentItems as $cart) {
                if (in_array($cart->item_id, $ids)) {
                    $qty = (int) $cart->qty;

                    if (!$qty) {
                        $qty = 1;
                    }

                    $item_total = $cart->price * $qty;
                    $items_total += $item_total - $cart->discount;
                    $item_total = $item_total - $cart->discount;

                    $cart->paid = $item_total;
                    $cart->save();

                    $object = Template::loadItemByType($cart->type, $cart->item_id);
                    $object->setPaid(true);

                    (\App::make(PaymentService::class))
                        ->saveAdCouponAndIncreaseTimesUsed($payment, $object, $cart->type)
                    ;
                }
            }
        }

        Cart::clearCart();
    }

    public function checkForRecurring($item)
    {
        $object = Template::loadItemByType($item['type'], $item['item_id']);
        if($object->recurring) {
            return $object;
        }

        return false;
    }

    public function updateVault($vault_id, $cc, $user_id)
    {
        $vault = Vault::where('id', $vault_id)->where('user_id', $user_id)->first();
        if($vault) {
            $user = Sentinel::findById($user_id);
            $cc_date = $this->prepareCCDate($cc->get('cc_date'));

            $payment_data = array(
                'customer_vault_id' => $vault->vault_id,
                'ccnumber' => $cc->get('cc_number'),
                'ccexp' => $cc_date,
                'cvv' => $cc->get('cc_cvc'),
                'autorization_key' => $this->getAuthorizationKey(),
                'type' => 'recurring_update'
            );

            $expi = UniRequest::post($this->expi_url, $this->headers, $payment_data);
            if($expi->code == 200 && $expi->raw_body) {
                $jsonResponse = json_decode($expi->raw_body);
                if($jsonResponse->success && $vault_id = $jsonResponse->customer_vault_id) {

                } else throw new PaymentException($jsonResponse);
            } else throw new Exception(Lang::get('payments/message.error.could_not_get_response_from_payment_gateway'));
        }
    }

    public function prepareCCDate($cc_date)
    {
        $exploded_date = explode('/', $cc_date);
        if(isset($exploded_date[0]) && isset($exploded_date[1])) {
            if(strlen($exploded_date[1]) > 2) {
                $cc_date = $exploded_date[0] . substr($exploded_date[1], -2);
            } else {
                $cc_date = str_replace('/', '', $cc_date);
            }
        } else {
            //leaving this as it was before, but surely this should never happen
            $cc_date = str_replace('/', '', $cc->get('cc_date'));
        }
        return $cc_date;
    }

    /* ads, nearme or classifield object */
    //not finished - customer need to contact expi
    public function setupVault($object, $cc, $user_id)
    {
        $user = Sentinel::findById($user_id);
        $desc = Lang::get('payments/title.vault_set_description ', ['type' => $object->getType(), 'id' => $object->id]);
        //make 4 digit year useable
        $cc_date = $this->prepareCCDate($cc->get('cc_date'));

        $payment_data = array(
            'orderdescription' => $desc,
            'ccnumber' => $cc->get('cc_number'),
            'ccexp' => $cc_date,
            'cvv' => $cc->get('cc_cvc'),
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'city' => $user->city,
            'state' => $user->state,
            'zip' => $user->postal,
            'country' => $user->country,
            'email' => $user->email,
            'autorization_key' => $this->getAuthorizationKey(),
            'type' => $this->vault_code,
        );

        $expi = UniRequest::post($this->expi_url, $this->headers, $payment_data);
        if($expi->code == 200 && $expi->raw_body) {
            $jsonResponse = json_decode($expi->raw_body);
            if($jsonResponse->success && $vault_id = $jsonResponse->customer_vault_id) {
                $options = array(
                    'vault_id' => $vault_id,
                    'user_id' => $user_id
                );
                $object->vault_id = $this->isertVault($options);
                $object->save();
            } else throw new Exception(Lang::get('payments/message.error.could_not_set_recurring_payments'));
        } else throw new Exception(Lang::get('payments/message.error.could_not_get_response_from_payment_gateway'));
    }

    public static function chargeVault($vault_id, $amount, $desc = '')
    {
        $payment = new Payments();
        $payment_data = array(
            'order_description' => $desc,
            'amount' => $amount,
            'customer_vault_id' => $vault_id,
            'autorization_key' => $payment->getAuthorizationKey(),
            'type' => $payment->vault_sale_code,
        );

        $expi = UniRequest::post($payment->expi_url, $payment->headers, $payment_data);
        if($expi->code == 200 && $expi->raw_body) {
            $jsonResponse = json_decode($expi->raw_body);
            if($jsonResponse->success && $vault_id = $jsonResponse->customer_vault_id) {
                return $jsonResponse->transactionid;
            } else throw new Exception($jsonResponse->responsetext);
        } else throw new Exception(Lang::get('payments/message.error.could_not_get_response_from_payment_gateway'));

        return false;
    }

    public function refund($transaction_id, $amount, $payment_item_id = 0)
    {
        $payment_data = array(
            'type' => 'refund',
            'transaction_id' => $transaction_id,
            'amount' => $amount,
            'autorization_key' => $this->getAuthorizationKey()
        );

        $expi = UniRequest::post($this->expi_url, $this->headers, $payment_data);
        if($expi->code == 200 && $expi->raw_body) {
            if($expi->body->success) {
                try {
                    $refund = new Refunds();
                    $refund->payment_item_id = $payment_item_id;
                    $refund->created_by = Sentinel::getUser()->id;
                    $refund->amount = $amount;
                    $refund->transaction_id = $expi->body->transactionid;
                    $refund->save();

                } catch (ModelNotFoundException $e) {
                    throw new Exception($e->getMessage());
                }

                return true;
            } else throw new Exception($expi->body->msg);
        } else throw new Exception(Lang::get('payments/message.error.could_not_get_response_from_payment_gateway'));

        return false;
    }

    /**
     * Success payment.
     *
     * @param $paymentId
     *
     * @return bool
     */
    public static function successPayment($paymentId)
    {
        try {
            $discount = 0;
            $payment = Payments::where('id', '=', $paymentId)->firstOrFail();
            $payment->paid = 1;

            if (Session::has('coupon_code')) {
                $payment->coupon = Session::get('coupon_code');
            }

            $cartItems = PaymentItems::where('payment_id', '=', $payment->id)->get();

            if ($cartItems) {
                foreach ($cartItems as $item) {
                    $discount += $item->discount;

                    $object = Template::loadItemByType($item->type, $item->item_id);
                    $object->setPaid(true);

                    (\App::make(PaymentService::class))
                        ->saveAdCouponAndIncreaseTimesUsed($payment, $object, $item->type)
                    ;
                }
            }

            $payment->discount = $discount;
            $payment->save();

            Cart::clearCart();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return true;
    }

    public static function getTransactionsHistory($item_type, $item_id)
    {
        $transactions = array();
        $user = Sentinel::getUser();
        if($user->hasAccess(['ad_transaction_history'])) {
            $payments = PaymentItems::where('item_id', '=', $item_id)->where('type', 'LIKE', $item_type);
            if($payments->count()) {
                foreach($payments->get() as $payment) {
                    $date = Carbon::parse($payment->created_at);
                    $transactions[] = array(
                        'transaction_id' => $payment->payment->transaction_id,
                        'type' => $payment->payment->tranasction_type,
                        'amount' => Template::convertPrice($payment->paid),
                        'created_at' => $date->diffForHumans(),
                        'timestamp' => $date->timestamp,
                    );

                    $refunds = Refunds::where('payment_item_id', '=', $payment->id);
                    if($refunds->count()) {
                        foreach($refunds->get() as $refund) {
                            $date = Carbon::parse($refund->created_at);
                            $transactions[] = array(
                                'transaction_id' => $refund->transaction_id,
                                'type' => Refunds::getType(),
                                'amount' => Template::convertPrice($refund->amount),
                                'created_at' => $date->diffForHumans(),
                                'timestamp' => $date->timestamp,
                            );
                        }
                    }
                }
            }
            usort($transactions, function($a, $b) {
                if($a['timestamp'] == $b['timestamp']) return 0;
                return $a['timestamp'] < $b['timestamp'] ? 1:-1;
            });
        }
        return $transactions;
    }

    public static function isertVault(array $options = [])
	{
        if(!isset($options['user_id'])) {
            $user = Sentinel::getUser();
            $options['user_id'] = $user->id;
        }

		$vault = new Vault($options);
        if($vault->save()) {
            return $vault->id;
        }

        return false;
	}

    public static function doRecurring($object)
    {
        try {
            $vault = Vault::where('id', '=', $object->vault_id)->firstOrFail();
            $desc = Lang::get('payments/title.ad_renewal_payment', ['id' => $object->id]);

            $result = Payments::chargeVault($vault->vault_id, $object->renewal_cost, $desc);
            if($result) {
                $object->recurringRenewal();
                Payments::isertRecurringToTable($object, $result);

                return true;
            }
        } catch(Exception $e) {
            Log::error('Recurring renewal failed:');
            Log::error($e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::error('User vault not found');
        }

        return false;
    }

    public static function isertRecurringToTable($object, $transaction_id)
    {
        try {

            $type = $object->getType();
            if($type == 'classified') $type = 'classifieds';

            $payment = new Payments();
            $payment->user_id = $object->user_id;
            $payment->amount = $object->renewal_cost;
            $payment->transaction_id = $transaction_id;
            $payment->tranasction_type = 'recurring';
            $payment->paid = 1;
            $payment->save();

            $payment_item = new PaymentItems();
            $payment_item->payment_id = $payment->id;
            $payment_item->plan_id = 0;
            $payment_item->item_id = $object->id;
            $payment_item->qty = 1;
            $payment_item->type = $type;
            $payment_item->price = $object->renewal_cost;
            $payment_item->paid = $object->renewal_cost;
            $payment_item->save();

        } catch(Exception $e){
            Log::error($e->getMessage());
            return false;
        }

        return true;
    }
}
