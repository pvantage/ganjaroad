<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use DB;

use Illuminate\Support\Facades\Log;
use Sentinel;

use App\Plans;
use App\Helpers\Template;
use App\Helpers\HelloSign as HelloSignHelper;

class Ads extends Model implements SluggableInterface
{
    use SoftDeletes;
    use SluggableTrait;
  	use Taggable;

    protected $dates = ['deleted_at', 'last_updated'];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'ads';
    protected $type = 'ads';

    protected $fillable = ['position_id', 'title', 'slug', 'url', 'phone', 'published_from', 'published_to', 'published', 'paid', 'recurring', 'approved', 'company'];

    protected $guarded = ['id'];

    public function getType()
    {
        return $this->type;
    }

    public function positions()
    {
        return $this->belongsTo('App\AdsPositions', 'position_id');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function companydetails()
    {
        return $this->belongsTo('App\AdsCompanies', 'company', 'id');
    }

    protected function increment($column, $amount = 1, array $extra = [])
    {
        $this->timestamps = false;
        $increment = $this->incrementOrDecrement($column, $amount, $extra, 'increment');
        $this->timestamps = true;

        return $increment;
    }

    public function save(array $options = [])
    {
        if(Template::isAdminRoute()) {
            try {
                $old_data = Ads::where('id', '=', $this->id)->firstOrFail();
                if($this->paid != $old_data->paid) {
                    $now = Carbon::now();

                    $this->last_updated = $now;
                }
            } catch (ModelNotFoundException $e) {}
        } /*else {
            try {
                $old_data = Ads::where('id', '=', $this->id)->firstOrFail();
                if($old_data->isActive() && !$this->active) {
                    Template::sendUnpublishedEmail($this);
                }
            } catch (ModelNotFoundException $e) {}
        }*/

        if(!$this->recurring) $this->recurring = 0;

        return parent::save();
    }

    public function delete()
    {
        if($this->isActive()) {
            Template::sendUnpublishedEmail($this);
        }

        return parent::delete();
    }

    public function setPaid($update_dates = true, $renewal_cost = 0, $renew_dates = false)
    {
        $now = Carbon::now();
        if($update_dates || $this->last_updated->timestamp <= 0) {
            $this->last_updated = $now;
        }

        if($renew_dates) {
            $published_from = Carbon::parse($this->published_from);
            $published_to = Carbon::parse($this->published_to);

            $days = $published_from->diffInDays($published_to);

            $this->published_from = $now;
            $this->published_to = $now->addDays($days);
        }

        if($renewal_cost) {
            $this->renewal_cost = $renewal_cost;
        } else {
            $this->renewal_cost = $this->getCost();
        }

        $this->paid = 1;
        $this->published = 1;

        Log::info('Ads ad setPaid: ' . $this->toJson());

        return $this->save();
    }

    public function unPaid($edit = false)
    {
        if($edit) {

        } else {
            //maybe email that ad was unpublished
        }
        $this->paid = 0;
        $this->published = 0;

        Log::info('Ads ad unPaid: ' . $this->toJson());

        return $this->save();
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
            $ads_expire = Template::getSetting('ads_expire');
            $days_expire = $ads_expire->diffInDays($now);

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

    public function isActive()
    {
        $now = Carbon::now();
        $approval = (bool)Template::getSetting('ads_approval');
        if($this->published == 1 && $this->paid == 1 && ($this->published_from <= $now || !$this->published_from) && ($this->published_to > $now || !$this->published_to)) {
            if($approval) {
                if($this->approved == 1) {
                    return true;
                }
            } else return true;
        }

        return false;
    }

    public static function getActive($include_date = true)
    {
        $approval = (bool)Template::getSetting('ads_approval');

        $query = Ads::where(function($query) use ($approval) {
            if($approval) $query->where('approved', '=', 1);
        })->where('published', '=', 1)->where('paid', '=', 1);

        if($include_date) {
            $query->where(function($query)
            {
                $query->where('published_from', '<=', Carbon::now());
                $query->orWhereNull('published_from');
            })->where(function($query)
            {
                $query->where('published_to', '>', Carbon::now());
                $query->orWhereNull('published_to');
            });
        }

        return $query;
    }

    public static function getPendingAds()
    {
        $approval = (int)Template::getSetting('ads_approval');
        if($approval) {
            $ads = Ads::where(function($query) {
                /*$query->where('published_from', '<=', Carbon::now());
                $query->orWhereNull('published_from');*/
            })->where(function($query) {
                /*$query->where('published_to', '>', Carbon::now());
                $query->orWhereNull('published_to');*/
            })->where('approved', '=', 0)->where('published', '=', 1)->where('paid', '=', 1);
        } else $ads = array();

        return $ads;
    }

    public function activeByUser()
    {
        return $this->published;
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
        $this->setPaid(true, $this->renewal_cost, true);

        return true;
    }

    public static function reportsQuery()
    {
        return DB::table('ads')
                ->join('users', 'users.id', '=', 'ads.user_id')
                ->join('ads_companies as company', 'company.id', '=', 'ads.company')
                ->select([
                    'ads.user_id',
                    'ads.title as ads_title',
                    'ads.phone as ads_phone',
                    DB::raw("CONCAT(users.first_name,' ',users.last_name) as fullname"),
                    'users.email as user_email',
                    'users.state as user_state',
                    'users.city as user_city',
                    'company.title as ads_company',
                    'company.notes as ads_company_notes',
                ])
                ->whereNull('ads.deleted_at');
    }

    public function getAdminAgreementTxt()
    {
        return HelloSignHelper::getAdminAgreementTxt($this->id, 'ads');
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
