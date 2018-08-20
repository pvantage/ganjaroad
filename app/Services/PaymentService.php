<?php

namespace App\Services;

use App\Coupons;
use App\AdCoupon;

/**
 * Class PaymentService.
 *
 * @package App\Services
 */
class PaymentService
{
    /**
     * Save ad coupon and increase times used.
     *
     * @param $payment
     * @param $object
     * @param $adType
     *
     * @return bool
     */
    public function saveAdCouponAndIncreaseTimesUsed($payment, $object, $adType)
    {
        if (!$payment || !$payment->coupon) {
            return false;
        }

        $coupon = Coupons::getCouponByCode($payment->coupon);

        if (!$object || !$coupon) {
            return false;
        }

        AdCoupon::create(
            [
                'ad_id' => $object->id,
                'ad_type' => $adType,
                'coupon_id' => $coupon->id,
            ]
        );

        $coupon->increment('times_used');

        return true;
    }
}
