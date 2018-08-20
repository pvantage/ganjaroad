<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\RefundsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Redirect;
use Exception;
use Form;

use Datatables;

use App\Refunds;
use App\Payments;
use App\Helpers\Template;

class RefundsController extends WeedController
{
    public function index()
    {
        $refunds = Refunds::all();

        return View('admin.refunds.index', compact('refunds'));
    }
	
	public function getModalDelete(Refunds $refunds)
    {
        $model = 'refunds';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('delete/refunds', ['id' => $refunds->id]);
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('refunds/message.error.delete', compact('id'));
            return View('admin/layouts/modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }
	
	public function destroy(Refunds $refunds)
    {
        if ($refunds->delete()) {
            return redirect('admin/refund')->with('success', trans('refunds/message.success.delete'));
        } else {
            return redirect('admin/refund')->withInput()->with('error', trans('refunds/message.error.delete'));
        }
    }
	
	public function dataList()
    {
        if(\Request::ajax()) {
            $refunds = Refunds::all();

            return Datatables::of($refunds)
                ->edit_column('author', function($refund){
                    return Form::Author($refund->created_by);
                })
				->add_column('item_type', function($refund) {
                    $item = $refund->getItem();
					if($item) return $item->getType();
					
					return '';
                })
				->add_column('item_link', function($refund) {
                    $item = $refund->getItem();
					if($item) {
						return '<a href="'.Template::getItemAdminLink($item->getType(), $item->id).'" target="_blank">'.$item->title.'</a>';
					}
					
					return '';
                })
                ->edit_column('amount', function($refund){
                    return Template::convertPrice($refund->amount);
                })
                ->edit_column('created_at', function($refund){
                    return $refund->created_at->diffForHumans();
                })
                ->add_column('actions', function($refund) {
                    $actions = '<a href="'. route('confirm-delete/refund', $refund->id) .'" data-toggle="modal" data-target="#delete_confirm">
                        <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="'.trans('refunds/table.delete_refund').'"></i>
                    </a>';

                    return $actions;
                })
                ->make(true);
        }
    }
	
	public function refund(RefundsRequest $request)
    {
        $amount = (float)$request->get('amount', 0);        
        $transaction_id = $request->get('transaction_id');
		$payment = Payments::where('transaction_id', 'LIKE', $transaction_id)->firstOrFail();
        /*try {
            $payment = Payments::where('transaction_id', 'LIKE', $transaction_id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return Redirect::back()->withInput()->with('error', trans('refunds/message.error.transaction_id_does_not_exits'));
        }*/
        
        $payment_item_id = 0;
        $object = Template::loadItemByType($request->get('type'), $request->get('item_id'));
        if(Refunds::getItemTransactionMaxRefund($transaction_id, $object)) {
            foreach($payment->items as $item) {
                if($item->item_id == $request->get('item_id')) {
                    $payment_item_id = $item->id;
                }
            }
        } else return Redirect::back()->withInput()->with('error', trans('refunds/message.error.transaction_does_not_have_any_items'));

        try {
            $gateway = new Payments();
            $gateway->refund($transaction_id, $amount, $payment_item_id);
            
            return Redirect::back()->withInput()->with('success', trans('refunds/message.success.amount_successfully_refunded'));
        } catch (Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
        
        return Redirect::back();
    }
}
