<?php

namespace App;

use Carbon\Carbon;
use Session;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use Lang;

use App\Plans;
use App\PaymentItems;
use App\Helpers\Template;
use App\Helpers\HelloSign as HelloSignHelper;
use App\Coupons;

class Cart extends Model
{

	public static function getCart()
    {
        $items = Session::get('cart', array());
        
        return $items;
    }
	
	public static function clearCart()
    {
        Session::set('cart', array());
        Session::forget('coupon_code');
        HelloSignHelper::forgetSessionSignature();
    }
	
    public static function addToCart($item)
    {
        $items = Session::get('cart', array());
        foreach($items as $key => $cart_item) {
            if($cart_item['item_id'] == $item['item_id'] && $cart_item['type'] == $item['type']) {
                //already in cart
                $object = Template::loadItemByType($item['type'], $item['item_id']);
                $qty = Cart::getAddToCartQty($item['type'], $object);
                
                if($qty != $cart_item['qty']) {
                    $items[$key]['qty'] = $qty;

                    Session::set('cart', $items);
                }
                
                return true;
            }
        }
        $items[] = $item;
        Session::set('cart', $items);
        
        return true;
    }
    
	public static function insertToCart($type, $item)
    {
        $object = Template::loadItemByType($type, $item->id);
        $active = $item->activeByUser();
        if($active) {
            $plan = Plans::getItemPaymentPlan($type, $item);
            if($plan) {
                $plan_id = $plan->id;
            } else $plan_id = 0;

            $price = Plans::getItemPrice($type, $item);
            $qty = Cart::getAddToCartQty($type, $item);

            //check if was not already paid
            $paid = PaymentItems::validatePaid($type, $item->id);

            $discount = 0;
            if(Session::has('coupon_code')) {
                $code = Session::get('coupon_code');
                $coupon = Coupons::getCouponByCode($code);
                $item_price = $price * $qty;
                $discount = Coupons::getDiscountByCoupon($coupon, $item_price);
            }

            $row_total = $price * $qty;
            $total = $row_total - $paid;
            if($total > 0) {
                $item->unPaid(true); //turn of ad until its paid
                $item->save();
            }

            $cart_item = array(
                'type' => $type,
                'title' => $item->title,
                'item_id' => $item->id,
                'plan_id' => $plan_id,
                'qty' => $qty,
                'price' => $price,
                'paid' => $paid,
                'discount' => $discount,
            );

            if($total > 0) {
                Cart::addToCart($cart_item);
            } else {
                $object->setPaid(false, $row_total);
            }
        } else $cart_item = array();
        
        return $cart_item;
    }
	
	public static function getAddToCartQty($type, $item)
    {
        $qty = 1;
        if($type == 'ads') {
            $start_date = new DateTime($item->published_from);
            $end_date = new DateTime($item->published_to);
            $diff = $start_date->diff($end_date)->m + ($start_date->diff($end_date)->y * 12);
            
            if($diff) $qty = $diff;
        } elseif($type == 'classifieds') {
            $qty = $item->countMulticity();
        }

        return $qty;
    }
	
	public static function removeItemFromCart($item_id)
    {
        $items = Session::get('cart', array());
        foreach($items as $key => $cart_item) {
            if($cart_item['item_id'] == $item_id) {
                unset($items[$key]);
            }
        }
        
        Session::set('cart', $items);

        return true;
    }
	
	public static function renderTotal($show_currency = true)
    {
        $items = Session::get('cart', array());
        $total = 0;
        $paid = 0;
        foreach($items as $cart_item) {
            $qty = (int)$cart_item['qty'];
            if(!$qty) $qty = 1;
            $total += $cart_item['price'] * $qty;
            if(isset($cart_item['paid']) && $cart_item['paid']) {$total -= $cart_item['paid'];}
            if(isset($cart_item['discount']) && $cart_item['discount']) $total -= $cart_item['discount'];
        }
        
        if($show_currency) $total_price = Template::convertPrice($total);
        else $total_price = $total;

        return $total_price;
    }
    
    public static function applyCoupon(Coupons $coupon)
    {
        $total = $coupon->max_amount;
        $code = false;
        $items = Cart::getCart();
        foreach($items as $key => $item) {
            if($total > 0) {
                $item_price = $item['price'] * $item['qty'];
                $discount = Coupons::getDiscountByCoupon($coupon, $item_price);

                $total -= $discount;
                if($total < 0) {
                    $discount = $item_price - abs($total);
                }
                if($discount) {
                    $items[$key]['discount'] = $discount;
                    Session::set('coupon_code', $coupon->code);
                    $code = true;
                }
            }
        }
        
        Session::set('cart', $items);
        return $code;
    }
    
    public static function getTotalDiscount($show_currency = true)
    {
        $items = Session::get('cart', array());
        $discount = 0;
        foreach($items as $cart_item) {
            if(isset($cart_item['discount']) && $cart_item['discount']) $discount += $cart_item['discount'];
        }
        
        if($show_currency) $discount = Template::convertPrice($discount);
        
        return $discount;
    }
    
    /* functions for HelloSign */
    public static function getProjectName()
    {
        $name = array();
        $items = Cart::getCart();
        foreach($items as $key => $item) {
            $name[] = $item['title'];
        }
        
        return implode(', ', $name);
    }
    
    public static function getAdsPricing()
    {
        $pricing = array();
        $items = Cart::getCart();
        foreach($items as $key => $item) {
            $check_box = '';
            $item_object = Template::loadItemByType($item['type'], $item['item_id']);
            if($item_object) {
                switch($item['type']) {
                    case('ads'): $title = Lang::get('payments/title.hello_sign_type_ads'); break;
                    case('nearme'): if($item_object->category) $title = $item_object->category->title . ' ' . Lang::get('general.ad'); break;
                    case('classifieds'): 
                        $title = Lang::get('payments/title.hello_sign_type_classified_multicity', ['cities' => $item['qty']]);
                        break;
                }

                $price = Template::convertPrice($item['price'] * $item['qty']);

                $pricing[] = $check_box . $title . ' ' . $price;
            }
        }
        
        return implode('\\n', $pricing);
    }
    
    public static function getAdsRecurring()
    {
        
        $recurring = array();
        $items = Cart::getCart();
        foreach($items as $key => $item) {
            
            $check_box = '';
            $time_string = '';
            $item_object = Template::loadItemByType($item['type'], $item['item_id']);
            if(!$item_object || !$item_object->recurring) continue;

            if($item['type'] == 'ads') {
                /*$start = $item_object->published_from;
                $end = $item_object->published_to;
                $days = clone $end_date->diffInDays($start);
                if($days < 30) {
                    if(($days % 7) == 0) {
                        $weeks = $days / 7;
                        $every = Lang::get('general.week') . ' ' . $weeks;
                        $time_string = Lang::get('general.every_time_string', ['time_sting' => $every]);
                    } else {
                        $every = Lang::get('general.days') . ' ' . $days;
                        $time_string = Lang::get('general.every_time_string', ['time_sting' => $every]);
                    }
                } else {
                    $start_months = $start->month;
                    $end_months = $end->month;
                    
                    $diff = $end_weeks - $end_months;
                    if($diff == 1) $every = Lang::get('general.month');
                    else $every = $diff . ' ' . Lang::get('general.months');

                    $time_string = Lang::get('general.every_time_string', ['time_sting' => $every]);
                }*/
                $qty = $item['qty'];
                switch($qty) {
                    case(1): $time_string = Lang::get('general.monthly');break;
                    case(2): $time_string = Lang::get('general.bi-monthly');break;
                    case(6): $time_string = Lang::get('general.bi-annual');break;
                    case(12): $time_string = Lang::get('general.annual');break;
                    
                    default:
                        $every = $qty . ' ' .Lang::get('general.months');
                        $time_string = Lang::get('general.every_time_string', ['time_sting' => $every]);
                }
            } else {
                if($item['type'] == 'nearme') {
                    $config = Settings::where('code', '=', 'nearme_expire')->firstOrFail();
                    $values = unserialize($config->value);
                } else {
                    //$config = Settings::where('code', '=', 'classifieds_expire')->firstOrFail();
                    $values = array(
                        'input' => $item_object->recurring_period,
                        'select' => $item_object->recurring_period_type,
                    );
                }
                
                if($values['input'] == 1 || $values['input'] == 2) {
                    switch($values['select']) {
                        case('w'):
                            if($values['input'] == 1) $time_string = Lang::get('general.weekly');
                            else $time_string = Lang::get('general.bi-weekly');
                            break;
                        case('m'):
                            if($values['input'] == 1) $time_string = Lang::get('general.monthly');
                            else $time_string = Lang::get('general.bi-monthly');
                            break;
                        case('y'):
                            if($values['input'] == 1) $time_string = Lang::get('general.annual');
                            else $time_string = Lang::get('general.bi-annual');
                            break;
                    }
                } else {
                    switch($values['select']) {
                        case('w'):
                            if($values['input'] == 1) $period = Lang::get('general.week');
                            else $period = $values['input'] . ' ' . Lang::get('general.weeks');
                        case('m'):
                            if($values['input'] == 1) $period = Lang::get('general.month');
                            else $period = $values['input'] . ' ' . Lang::get('general.months');
                            break;
                        case('y'):
                            if($values['input'] == 1) $period = Lang::get('general.year');
                            else $period = $values['input'] . ' ' . Lang::get('general.years');
                            break;
                    }
                    $every = $period;
                    $time_string = Lang::get('general.every_time_string', ['time_sting' => $every]);
                }
            }
            
            $date = Carbon::now();
            $suffix = Lang::get('payments/title.hello_sign_recurring_suffix', ['date' => $date->format('m/d/y')]);
            $recurring[] = $check_box . $time_string . ' ' . $suffix;
        }
        
        return implode('\\n', $recurring);
    }
    
    public static function getCartSignatureId()
    {
        $cart = Cart::getCart();
        $session_signature = Session::get('signature_items', array());
        if($session_signature && isset($session_signature['signature'])) {
            if($session_signature && isset($session_signature['signature'])) {
                $signature_request_id = $session_signature['signature']['request_id'];
                $signature_id = $session_signature['signature']['signature_id'];
                unset($session_signature['signature']);
                
                if($cart == $session_signature) {
                    return $signature_id;
                }
            }            
        }
        return false;
    }
    
    /* functions for HelloSign */
    
    public static function hasRecurringItem()
    {
        $items = Cart::getCart();
        foreach($items as $key => $item) {
            $object = Template::loadItemByType($item['type'], $item['item_id']);
            if($object->recurring) return true;
        }
        
        return false;
    }
}
