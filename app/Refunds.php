<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Refunds extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'refunds';
    protected $guarded = ['id'];
	protected $type = 'refund';
    
    public static function getType()
    {
        $refunds = new Refunds();
        
        return $refunds->type;
    }
    
	public function payment()
    {
        return $this->belongsTo('App\Payments', 'payment_id');
    }
	
	public function paymentItem()
    {
        return $this->belongsTo('App\PaymentItems', 'payment_item_id');
    }
	
	public function author()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    
    public function getItem()
    {
        return $this->paymentItem->getItem();
    }
	
	public static function getItemTransactionMaxRefund($transaction_id, $object)
	{
		try {
            $payment = Payments::where('transaction_id', 'LIKE', $transaction_id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return 0;
        }
		
		$payment_item = false;
		if($payment->items->count() > 0) {
            foreach($payment->items as $item){
                if($item->item_id == $object->id) {
				
                    if($item->paid > 0) {
                        $payment_item = $item;
                        break;
                    }
                }
            }
        }

        if($payment_item) {
			$refunds = Refunds::where('payment_item_id', '=', $payment_item->id)->sum('amount');
			$max = $payment_item->paid - $refunds;

			return $max;
		}
		
		return 0;
	}
}
