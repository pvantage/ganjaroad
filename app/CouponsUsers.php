<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CouponsUsers extends Model 
{

    protected $table = 'coupons_users';
    protected $fillable = ['user_id', 'coupon_id'];
    protected $guarded = ['id'];
	
	public function coupon()
    {
        return $this->belongsTo('App\Coupons', 'coupon_id');
    }
}
