<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lang;
use Sentinel;
use Carbon\Carbon;
use App\Helpers\Template;

use App\CouponsUsers;

class Coupons extends Model
{
    use SoftDeletes;

    protected $dates = ['start_date', 'end_date'];
    protected $table = 'coupons';

    protected $fillable = ['code', 'active', 'uses_per_coupon', 'uses_per_user', 'type', 'discount', 'max_amount', 'published_from', 'published_to'];
    
    protected $guarded = ['id'];
	
	public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function getType()
    {
        if($this->type == 0) return 'fixed';
        else return 'percentage';
    }
    
    public function getUserCoupons()
    {
        return $this->hasMany('App\CouponsUsers', 'coupon_id');
    }
    
    public function countUsedCoupons($user_id)
    {
        return $this->getUserCoupons()->where('user_id', '=', $user_id)->count();
    }
    
    public static function getTypes()
    {
        $types = array(
            0 => Lang::get('coupons/form.type-fixed'),
            1 => Lang::get('coupons/form.type-percentage'),
        );
        
        return $types;
    }
    
    public static function isValid($code)
    {
        try {
            $coupon = Coupons::where(function($query)
            {
                $query->where('published_from', '<=', Carbon::now());
                $query->orWhereNull('published_from');
            })->where(function($query)
            {
                $query->where('published_to', '>', Carbon::now());
                $query->orWhereNull('published_to');
            })->where('code', '=', $code)->where('active', '=', 1)->firstOrFail();
            
            if(User::check()) {
                $user_id = Sentinel::getUser()->id;
                $count = $coupon->countUsedCoupons($user_id);

                if($count >= $coupon->uses_per_user) return false;
            }
                        
            if($coupon->uses_per_coupon >  $coupon->times_used) return $coupon;
        } catch (ModelNotFoundException $e) {
            
        }
        
        return false;
    }
    
    public static function getCouponByCode($code)
    {
        try {
            $coupon = Coupons::where('code', '=', $code)->firstOrFail();
            return $coupon;
        } catch (ModelNotFoundException $e) {}
        
        return false;
    }
    
    public static function getDiscountByCoupon(Coupons $coupon, $amount)
    {
        $discount = 0;
        if($coupon->getType() == 'fixed') {
            $discount = $coupon->discount;
        } else {
            $discount = $amount * ($coupon->discount / 100);
        }
        
        if($discount > $amount) $discount = $amount;
        
        return $discount;
    }
}
