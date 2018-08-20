<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lang;
use Carbon\Carbon;

use App\Helpers\Template;

class PaymentItems extends Model
{
    protected $table = 'payment_items';

    protected $guarded = ['id'];

    public function payment()
    {
        return $this->belongsTo('App\Payments', 'payment_id');
    }
    
    public function refunds()
    {
        return $this->hasMany('App\Refunds', 'payment_item_id');
    }
    
    public function getItem()
    {
        return Template::loadItemByType($this->type, $this->item_id);
    }
    
    public function getItemTitle()
    {
        $item = $this->getItem();
        if($item) return $item->title;
        else return Lang::get('general.item_removed');
    }
    
    public static function validatePaid($type, $item_id)
    {
        $item = Template::loadItemByType($type, $item_id);
        $plan = Plans::getItemPaymentPlan($type, $item);

        $paid = 0;
        //if($item->paid) {
        $start_date = Carbon::parse($item->last_updated)->subHour();
        if ($type == 'ads') {
            $end_date = $item->published_to;
        } else {
            $end_date = $item->active_to;
        }
        if ($start_date !== null && $end_date !== null) {
            //$cart = PaymentItems::where('type', '=', $type)->where('item_id', '=', $item_id);
            $cart = PaymentItems::where('type', '=', $type)->where('item_id', '=', $item_id)->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date);
            /*if($cart->count()) {
                /*$last_transaction = $cart->latest()->first();
                if(!$last_transaction->qty) $qty = 1;
                else $qty = $last_transaction->qty;
                $paid += $last_transaction->price * $qty;
                $last_transaction = $cart->latest()->first()->id;
                foreach($cart->get() as $item) {
                    if($last_transaction == $item->payment_id) $paid += $item->price * $item->qty;
                }*/
            /*foreach($cart->get() as $item) {
                $paid += $item->paid;
            }
        }*/
            //}

            $paid = $cart->sum('paid');
        }
        /*if($plan) {
            if($paid >= $plan->amount) {
                return true;
            }
        }*/

        return $paid;
    }
    
    public static function insertTransactionItem(Payments $payment, $item)
    {
        $cart_item = new PaymentItems();
        $cart_item->payment_id = $payment->id;
        $cart_item->plan_id = $item['plan_id'];
        $cart_item->item_id = $item['item_id'];
        $cart_item->qty = $item['qty'];
        $cart_item->type = $item['type'];
        $cart_item->price = $item['price'];
        if(isset($item['paid'])) $cart_item->paid = $item['paid'];
        if(isset($item['discount'])) $cart_item->discount = $item['discount'];
        
        return $cart_item->save();
    }
}
