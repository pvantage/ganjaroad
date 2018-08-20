<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Sentinel;

use App\Cart;
use App\HelloSignItems;

class HelloSign extends Model {

    protected $table = 'hellosign';
    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'signature_id', 'pdf'];
	protected $dates = ['deleted_at'];
    
	public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function items()
    {
        return $this->hasMany('App\HelloSignItems', 'hellosign_id');
    }
    
    public function saveSigntureItems()
    {
        $cart_signature = Cart::getCartSignatureId();
        if($this->signature_id == $cart_signature) {
            $items = Cart::getCart();
            HelloSignItems::where('hellosign_id', '=', $this->id)->delete();
            
            foreach($items as $key => $item) {
                $params = array(
                    'hellosign_id' => $this->id,
                    'item_id' => $item['item_id'],
                    'item_type' => $item['type'],
                );
                $hellosign_item = new HelloSignItems($params);
                $hellosign_item->save();
            }
        }
    }
	
    public static function saveSignature($signature_id, $signature_request_id = false, $signed = 0, $user_id = 0)
    {
        try {
			$signature = HelloSign::where('signature_id', '=', $signature_id)->firstOrFail();
		} catch (ModelNotFoundException $e) {
            $signature = new HelloSign();
			$signature->signature_id = $signature_id;
        }
        
        if($signature_request_id) $signature->signature_request_id = $signature_request_id;
        if($user_id) $signature->user_id = $user_id;
        if($signed) $signature->signed = 1;
        $signature->save();
		
        $signature->saveSigntureItems();
        
		return $signature;
    }
}
