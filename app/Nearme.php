<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Request;
use Carbon\Carbon;
use DB;

use Sentinel;
use Gregwar\Image\Image;

use App\Images;
use App\Plans;
use App\NearmeItems;
use App\Helpers\Template;
use App\Helpers\HelloSign as HelloSignHelper;

class Nearme extends Model implements SluggableInterface
{

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['deleted_at', 'last_updated', 'active_to'];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'nearme';
    protected $type = 'nearme';

    protected $fillable = ['category_id', 'user_id', 'title', 'slug', 'content', 'url', 'email', 'phone', 'first_time', 'facebook', 'instagram', 'twitter', 'other_address', 'full_address', 'delivery', 'atm', 'min_age', 'wheelchair', 'security', 'credit_cards', 'hours', 'published', 'active', 'paid', 'recurring', 'approved', 'lattitude', 'longitude', 'address1', 'address2', 'country', 'city', 'state', 'zip', 'active_to'];

    protected $guarded = ['id'];

    public function getType()
    {
        return $this->type;
    }

    public function save(array $options = [])
    {
        if(Template::isAdminRoute()) {
            try {
                $old_data = Nearme::where('id', '=', $this->id)->firstOrFail();
                if($this->paid != $old_data->paid) {
                    $this->last_updated = Carbon::now();
                }
            } catch (ModelNotFoundException $e) {}
        } else {
            try {
                $old_data = Nearme::where('id', '=', $this->id)->firstOrFail();
                if($old_data->isActive(true, false) && !$this->active) {
                    Template::sendUnpublishedEmail($this);
                }
            } catch (ModelNotFoundException $e) {}
        }

        if(is_array($this->hours)) $this->hours = serialize($this->hours);
        if(!$this->other_address) $this->other_address = 0;
        if(!$this->recurring) $this->recurring = 0;
        
        return parent::save();
    }

    public function delete()
    {
        if($this->isActive(true, false)) {
            Template::sendUnpublishedEmail($this);
        }

        return parent::delete();
    }

    public function setPaid($update_dates = true, $renewal_cost = 0)
    {
        if($update_dates || $this->active_to->timestamp <= 0) { // or dates not set yet
            $now = Carbon::now();
            $this->last_updated = $now;
            $active_to = $now;
            if($this->active_to) {
                $old_active_to = Carbon::parse($this->active_to);
                if($old_active_to > $now) $active_to = $old_active_to;
            }

            if($this->recurring_period_type) {
                $new_date = $this->getRecurringExpireDate();
                $days = $new_date->diffInDays($now);
            } else {
                $nearme_expire = Template::getSetting('nearme_expire');
                $days = $nearme_expire->diffInDays($now);
            }

            $active_to->addDays($days);
            $this->active_to = $active_to;
        }

        if($renewal_cost) {
            $this->renewal_cost = $renewal_cost;
        } else {
            $this->renewal_cost = $this->getCost();
        }

        $this->paid = 1;

        Log::info('Nearme ad setPaid: ' . $this->toJson());

        return $this->save();
    }

    public function unPaid($edit = false)
    {
        if($edit) {

        } else {
            //maybe email that ad was unpublished
        }
        $this->paid = 0;

        Log::info('Nearme ad unPaid: ' . $this->toJson());

        return $this->save();
    }

    protected function increment($column, $amount = 1, array $extra = [])
    {
        $this->timestamps = false;
        $increment = $this->incrementOrDecrement($column, $amount, $extra, 'increment');
        $this->timestamps = true;

        return $increment;
    }

    public function category()
    {
        return $this->belongsTo('App\NearmeCategory');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function items()
    {
        return $this->hasMany('App\NearmeItems', 'nearme');
    }

    public function getNearmecategoryAttribute()
    {
        return $this->category->lists('id');
    }

    public function formatAddressShort($force = false)
    {
        $address = array();

        if(!$force && $this->other_address) {
            return $this->full_address;
        } else {
            if($this->address1) $address[] = $this->address1;
            if($this->address2) $address[] = $this->address2;
            if($this->city) $address[] = $this->city;
            if($this->state) $address[] = $this->state;
            if($this->zip) $address[] = $this->zip;

            return implode(', ', $address);
        }

        return '';
    }

    public function formatAddressFull($force = false)
    {
        if(!$force && $this->other_address) {
            return $this->full_address;
        } else {
            $address = array();
            if($this->address1) $address[] = $this->address1;
            if($this->address2) $address[] = $this->address2;
            if($this->city) $address[] = $this->city;
            if($this->state) $address[] = $this->state;
            if($this->zip) $address[] = $this->zip;

            if($this->country) {
                $countries = Template::getCountriesList(false);
                if(isset($countries[$this->country])) {
                    $address[] = $countries[$this->country];
                }
            }

            return implode(', ', $address);
        }

        return '';
    }

    public function getIcon()
    {
        if($icon = $this->category->icon) {
            return Template::getNearMeCategoryImageDir().$icon;
        } else return Template::getSetting('nearme_default_icon');
    }

    public function isActive()
    {
        $approval = Template::getSetting('nearme_approval');

        /*$isactive = $this->where('published', '=', 1)->where('active', '=', 1)->where('paid', '=', 1);
        if($approval) $isactive->where('approved', '=', 1);
        
        return $isactive->count();*/
        //return $this->getActive()->where('id', '=', $this->id)->count();

        $now = Carbon::now();
        $approval = (bool)Template::getSetting('ads_approval');
        if($this->published && $this->active && $this->paid && $this->active_to > $now) {
            if($approval) {
                if($this->approved == 1) {
                    return true;
                }
            } else return true;
        }

        return false;

    }

    public function images()
    {
        return $this->hasMany('App\Images', 'item_id')->where('type', 'LIKE', $this->getType())->whereNull('deleted_at');
    }

    public function image()
    {
        /*$image = Images::where('item_id', '=', $this->id)->where('type', 'LIKE', $this->getType())->first();
        if($image) return $image->image;
        else return false;*/
        return $this->image;
    }

    public function getCost()
    {
        $plan = Plans::getItemPaymentPlan($this->getType(), $this);
        $price = 0;

        if($plan) {
            $price = $plan->amount;
        }

        if($this->recurring_period_type) {
            $now = Carbon::now();
            $new_date = $this->getRecurringExpireDate();
            $nearme_expire = Template::getSetting('nearme_expire');
            $days_expire = $nearme_expire->diffInDays($now);

            $reccuring_period = $new_date->diffInDays($now);
            $diff = round($reccuring_period / $days_expire * 100, 0);
            $new_price = $price * $diff / 100;
            if($this->recurring_discount > 0) {
                $new_price = $new_price - ($new_price * ($this->recurring_discount/100));
            }
        } else $new_price = $price;

        //leave $price for now
        return $price;
        //return $new_price;
    }

    public static function getActive($include_date = true)
    {
        $approval = Template::getSetting('nearme_approval');
        $now = Carbon::now();

        $query = Nearme::where(function($query) use ($approval) {
            if($approval) $query->where('approved', '=', 1);
        })->where('published', '=', 1)->where('active', '=', 1)->where('paid', '=', 1);

        if($include_date) $query->where('active_to', '>', $now);

        return $query;
    }

    public function activeByUser()
    {
        return $this->active;
    }

    public function getItemLastPaidTransaction()
    {
        $item = PaymentItems::with('payment')
            ->whereHas('payment', function($query){
                $query->where('paid', '=', 1);
            })
            ->where('item_id', '=', $this->id)
            ->where('type', 'LIKE', $this->getType())->orderBy('id');

        try {
            return $item->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function canBeRefunded()
    {
        $user = Sentinel::getUser();
        if($user && $user->hasAccess(['refund'])) {
            $last_transaction = $this->getItemLastPaidTransaction();
            if($last_transaction) $max_refund = Refunds::getItemTransactionMaxRefund($last_transaction->payment->transaction_id  , $this);
            else return false;

            if($max_refund > 0) return true;
        }

        return false;
    }

    public function getTransactionsHistory()
    {
        return Payments::getTransactionsHistory($this->getType(), $this->id);
    }

    public function recurringRenewal()
    {
        $this->setPaid(true, $this->renewal_cost);

        return true;
    }

    public static function reportsQuery()
    {
        return DB::table('nearme')
                ->join('users', 'users.id', '=', 'nearme.user_id')
                ->join('nearme_categories', 'nearme_categories.id', '=', 'nearme.category_id')
                ->select([
                    'nearme.user_id',
                    'nearme.title as nearme_title',
                    'nearme_categories.title as nearme_ad_type',
                    DB::raw("CONCAT(users.first_name,' ',users.last_name) as fullname"),
                    'users.email as user_email',
                    'users.state as user_state',
                    'users.city as user_city',
                    'nearme.state as nearme_state',
                    'nearme.city as nearme_city',
                    'nearme.phone as nearme_phone',
                    'nearme.email as nearme_email',
                ])
                ->whereNull('nearme.deleted_at');
    }

    public function getAdminAgreementTxt()
    {
        return HelloSignHelper::getAdminAgreementTxt($this->id, 'nearme');
    }

    public function getRecurringExpireDate()
    {
        $new_date = Carbon::now();
        switch($this->recurring_period_type) {
            case('d'): $new_date->addDays($this->recurring_period); break;
            case('w'): $new_date->addDays($this->recurring_period * 7); break;
            case('m'): $new_date->addMonth($this->recurring_period); break;
            case('y'): $new_date->addYears($this->recurring_period); break;
        }

        return $new_date;
    }
}
