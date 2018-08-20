<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdCoupon.
 *
 * @package App
 */
class AdCoupon extends Model
{
    /**
     * @var string
     */
    protected $table = 'ad_coupon';

    /**
     * @var array
     */
    protected $fillable = [
        'ad_id',
        'ad_type',
        'coupon_id',
    ];
}
