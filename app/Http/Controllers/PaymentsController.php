<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\PaymentsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Response;
use Redirect;
use Carbon\Carbon;
use Form;
use Validator;
use Session;
use Exception;
use Illuminate\Support\Facades\Input as Input;
use Lang;

use Datatables;
use Sentinel;

use App\Vault;
use App\Payments;
use App\PaymentItems;
use App\Cart;

use App\AdRenewOption;
use App\Ads;
use App\Classified;
use App\Nearme;

use PaymentException;
use App\Helpers\Template;
use App\Helpers\HelloSign as HelloSignHelper;

class PaymentsController extends WeedController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $payments = Payments::all();

        return View('admin.payments.index', compact('payments'));
    }

    public function userList()
    {
        $user = Sentinel::getUser();
        $transactions = Payments::where('user_id', $user->id)->get();

        return View('frontend.payments.user', compact('transactions'));
    }

    public function review()
    {
        $items = Cart::getCart();
        if($items) {
            $signed =  Template::signedAgreement();

            return View('frontend.payments.review', compact('items', 'signed'));
        } else {
            return redirect('pendingpayment');
        }
    }

    public function freeExpiredAdsReview()
    {
        $now = Carbon::now();
        $user = Sentinel::getUser();
        $classifieds = Classified::where('user_id', $user->id)
            ->where('recurring', 0)
            ->where('active_to', '<', $now)
            ->get();

        $ads = Ads::where('user_id', $user->id)
            ->where('recurring', 0)
            ->where('published_to', '<', $now)
            ->get();

        $nearmes = Nearme::where('user_id', $user->id)
            ->where('recurring', 0)
            ->where('active_to', '<', $now)
            ->get();

        $items = [
            'classifieds' => $classifieds,
            'ads' => $ads,
            'nearme' => $nearmes
        ];

        $adrenewoptions = [
            'classifieds' => AdRenewOption::where('renewal_type', 'classified')->get(),
            'ads' => AdRenewOption::where('renewal_type', 'banner_ad')->get(),
            'nearme' =>  AdRenewOption::where('renewal_type', 'nearme')->get()
        ];

        return View('frontend.payments.free_expired', compact('items', 'adrenewoptions'));
    }

    public function freeExpiredAdsReviewToCheckout(Request $request)
    {
        $data = $request->except('_token');

        foreach($data as $type => $items) {
            foreach($items as $id => $ad) {
                if($type == 'classifieds') {
                    $object = Classified::where('id', $id)->first();
                } elseif($type == 'ads') {
                    $object = Ads::where('id', $id)->first();
                } elseif($type == 'nearme') {
                    $object = Nearme::where('id', $id)->first();
                } else {
                    continue;
                }

                if($object) {
                    if(isset($ad['delete'])) {
                        if($ad['delete'] == 1) {
                            $object->delete();
                            continue;
                        }
                    }
                    if(isset($ad['renewal'])) {
                        $adrenewoption = AdRenewOption::where('id', $ad['renewal'])->first();
                        if($adrenewoption) {
                            $object->recurring = 1;
                            $object->recurring_period = $adrenewoption->renewal_time;
                            $object->recurring_period_type = $adrenewoption->renewal_time_period;
                            $object->recurring_discount = $adrenewoption->renewal_discount;
                        }
                        $object->save();
                        if ($object->id) {
                            $item = Cart::insertToCart($type, $object);
                        }
                    }
                }
            }
        }

        return redirect('review');
    }

    public function pending()
    {
        $items = Template::getUserNotPaidItems();

        return View('frontend.payments.pending', compact('items'));
    }

    public function getModalDelete(Payments $payments)
    {
        $model = 'payments';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/payments', ['id' => $payments->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('payments/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function destroy(Payments $payments)
    {
        if ($payments->delete()) {
            return redirect('admin/payments')->with('success', trans('payments/message.success.delete'));
        } else {
            return redirect('admin/payments')->withInput()->with('error', trans('payments/message.error.delete'));
        }
    }

    public function reviewRemoveItemConfirm($item)
    {
        $model = 'payments';
        $confirm_route = $error = null;

        try {
            $confirm_route = route('review/item/delete', ['id' => $item]);
            return View('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('payments/message.error.cart_item');
            return View('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function reviewRemoveItem($item)
    {
        Cart::removeItemFromCart($item);

        return redirect('review')->with('success', trans('front/general.review_item_removed'));
    }

    public function reviewAddItem($item, $type)
    {
        $item = Template::loadItemByType($type, $item);
        if($item) Cart::insertToCart($type, $item);

        return redirect('review')->with('success', trans('front/general.review_item_added'));
    }

    /**
     * Success payment.
     *
     * @param $paymentId
     *
     * @return mixed
     */
    public function successPayment($paymentId)
    {
        $payment = Payments::successPayment($paymentId);

        if (!$payment) {
            return Redirect::to('/');
        }
    }

    //without payment gateway
    public function failedPayment()
    {
        //dont do anything for testing redirect with faild

        return redirect('my-account')->withInput()->with('error', trans('front/general.payment_canceled'));

    }

    /**
     * Payment form.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function paymentForm(Request $request)
    {
        $user = Sentinel::getUser();
        $totalAmount = Cart::renderTotal(false);
        $isPaymentEnabled = Template::isDeveloper();
        $total = Cart::renderTotal(true);

        if ($isPaymentEnabled) {
            $signed = Template::signedAgreement();

            if (!$signed) {
                return redirect('review')->withInput()->with('error', trans('payments/message.error.sign_agreement'));
            }
        } else {
            $validator = Validator::make($request->all(), ['marketing' => 'required']);

            if ($validator->fails()) {
                return redirect('review')->with('error', trans('payments/message.error.marketing'));
            }
        }

        if (!$isPaymentEnabled) {
            $payment = new Payments();
            $payment->user_id = $user->id;
            $payment->amount = Cart::renderTotal(false);
            $payment->save();

            $this->showApprovalMsg($payment);
            $this->successPayment($payment->id);

            return redirect('my-account')->with('success', trans('payments/message.success.payment_accepted'));
        }

        if ($totalAmount > 0) {
            return View(
                'frontend.payments.process',
                [
                    'total' => $total,
                    'show_popup' => false,
                ]
            );
        } else if ($totalAmount == 0 && Cart::hasRecurringItem()) {
            return View(
                'frontend.payments.process',
                [
                    'total' => $total,
                    'show_popup' => true,
                ]
            );
        } else if ($totalAmount <= 0) {
            $payment = new Payments();
            $payment->user_id = $user->id;
            $payment->amount = Cart::renderTotal(false);
            $payment->save();

            $this->showApprovalMsg($payment);
            $this->successPayment($payment->id);

            return redirect('my-account')->with('success', trans('payments/message.success.payment_accepted'));
        }
    }

    public function showApprovalMsg($payment)
    {
        $ads_approval = (int)Template::getSetting('ads_approval');
        $classifieds_approval = (int)Template::getSetting('classified_approval');
        $nearme_approval = (int)Template::getSetting('nearme_approval');
        $approval_msg = false;

        $items = Cart::getCart();
        if($items) {
            foreach($items as $item) {
                switch($item['type']) {
                    case('ads'): if($ads_approval) $approval_msg = true; break;
                    case('nearme'): if($nearme_approval) $approval_msg = true; break;
                    case('classifieds'): if($classifieds_approval) $approval_msg = true; break;
                }
                PaymentItems::insertTransactionItem($payment, $item);
            }
        }



        if($approval_msg) {
            Session::set('notice', trans('payments/title.payment_success_approval_msg'));
        }
    }

    /**
     * Process payment.
     *
     * @param PaymentsRequest $request
     *
     * @return mixed
     */
    public function proccessPayment(PaymentsRequest $request)
    {
        $items = Cart::getCart();

        if (!$items) {
            return response()->json(['success' => false, 'msg' => trans('payments/message.error.no_items_in_cart')]);
        }

        try {
            (new Payments())->makePayment($request, Cart::renderTotal(false), Sentinel::getUser()->id, $items);

            Session::set('success', trans('payments/message.success.payment_accepted'));
        } catch (PaymentException $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'redirect' => route('my-account')]);
    }

    public function vaultList()
    {
        $vaults = [];
        $user = Sentinel::getUser();
        $results = Vault::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        //check if vault is connected with some ads
        if($results) {
            foreach($results as $vault) {
                if(Template::checkIfVaultHasAdConnected($vault)) {
                    $vaults[] = $vault;
                }
            }
        }
        return View('frontend.payments.vault_list', compact('vaults'));
    }

    public function updateVault($vault_id)
    {
        $user = Sentinel::getUser();
        $vault = Vault::where('id', $vault_id)->where('user_id', $user->id)->first();
        if($vault) {
            return View('frontend.payments.vault_update', compact('vault'));
        }
        return redirect('update/vault');
    }

    public function postUpdateVault(PaymentsRequest $request, $vault_id)
    {
        $gateway = new Payments();
        $user = Sentinel::getUser();

        $response = array();

        try {
            $result = $gateway->updateVault($vault_id, $request, $user->id);

            Session::set('success', 'Credit Card updated');

            $url = route('my-account');
            $response = array('success' => true, 'redirect' => $url);
        } catch(PaymentException $e) {
            $response = array('success' => false, 'msg' => $e->getMessage());
        } catch(Exception $e) {
            $response = array('success' => false, 'msg' => $e->getMessage());
        }

        return response()->json($response);
    }

    public function dataList()
    {
        if(\Request::ajax()) {
            $payments = Payments::all();

            return Datatables::of($payments)
                ->edit_column('author', function($payment){
                    return Form::Author($payment->user_id);
                })
                ->edit_column('amount', function($payment){
                    return Template::convertPrice($payment->amount);
                })
                ->edit_column('discount', function($payment){
                    return Template::convertPrice($payment->discount);
                })
                ->edit_column('paid', function($payment){
                    return Form::Published($payment->paid);
                })
                ->edit_column('created_at', function($payment){
                    return $payment->created_at->diffForHumans();
                })
                ->add_column('actions', function($payment) {
                    $actions = '<a href="'. route('confirm-delete/payments', $payment->id) .'" data-toggle="modal" data-target="#delete_confirm">
                        <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="'.trans('payments/table.delete-payments').'"></i>
                    </a>';

                    return $actions;
                })
                ->make(true);
        }
    }

    public function getAgreement()
    {
        $sign_url = HelloSignHelper::getForm();
        $notice = '';
        if(Template::signedAgreement()) {
            $notice = Lang::get('front/review.please_reload_page_and_proceed_to_checkout');
        }

        return View('frontend/hellosign/marketing_agreement', compact('sign_url', 'notice'));
    }

    public function helloSignPostCallback()
    {
        $input = Input::get('json');
        $data = json_decode($input, true);
        if(isset($data['event']['event_type'])) {
            $event_type = $data['event']['event_type'];
            if ($event_type == 'signature_request_downloadable') {

                //$signature_id = $data['event']['event_metadata']['related_signature_id'];
                $signature_id = $data['signature_request']['signatures'][0]['signature_id'];
                $signature_request_id = $data['signature_request']['signature_request_id'];
                if($signature_id) {
                    HelloSignHelper::saveSignature($signature_id, false, 1);
                    $file_saved = HelloSignHelper::saveFile($signature_request_id);
                }
            }

            return 'Hello API Event Received';
        }

        //return response()->json(array('success' => false));
    }

    public function helloSignGetCallback()
    {
        $event = Input::get('event');
        $signature_id = Input::get('signature_id');

        if($signature_id && $event == 'signature_request_signed') {
            if(Template::validateSignature($signature_id)) {
                HelloSignHelper::setSessionSignature($signature_id);

                return View('frontend/hellosign/successfully_signed');
            }
        }
    }

    public function downloadAgreement($id)
    {
        $file = HelloSignHelper::downloadAgreement($id);
        if($file) {
            return response()->download($file);
        } else {
            return redirect(route('dashboard'))->with('error', Lang::get('general.file_does_not_exist'));
        }
    }
}
