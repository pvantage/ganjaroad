<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Request;
use Lang;
use Carbon\Carbon;
use Sentinel;
use DB;
//use Cache;

use App\Issues;
use App\Payments;
use App\PaymentItems;
use App\Helpers\Template;
use App\ClassifiedCategory;
use App\Refunds;
use App\Helpers\HelloSign as HelloSignHelper;
use App\Helpers\CacheHelper;

class Classified extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;
  	use Taggable;

    protected $dates = ['deleted_at', 'last_updated', 'active_to'];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'classifieds';
    protected $type = 'classified';
    protected $fillable = ['title', 'slug', 'content', 'published', 'map_address', 'hide_map', 'lattitude', 'longitude', 'published', 'paid', 'approved', 'active', 'recurring', 'active_to'];
    protected $guarded = ['id'];
        
    public function getType()
    {
        return $this->type;
    }

    public function save(array $options = [])
    {
        if(Template::isAdminRoute()) {
            try {
                $old_data = Classified::where('id', '=', $this->id)->firstOrFail();
                
                if($this->paid != $old_data->paid) {
                    $now = Carbon::now();
                    
                    $this->last_updated = $now;
                }
            } catch (ModelNotFoundException $e) {}
        } else {
            try {
                $old_data = Classified::where('id', '=', $this->id)->firstOrFail();
                if($old_data->isActive(true, false) && !$this->active) {
                    Template::sendUnpublishedEmail($this);
                }
            } catch (ModelNotFoundException $e) {}
        }
        
        if(!$this->recurring) $this->recurring = 0;
        
        parent::save();
        Images::updateImages($this->getType(), $this->id, Request::get('images'));
        if(Request::method() == 'PUT') Issues::removeIssues($this->id);
        if($this->active && $issues = Request::get('issues')) {
            $issue = Issues::insertIssues($this->id, $this->getType(), $issues);
            if($issue) {
                $link = Template::classifiedEditLink($this->id, $this->slug);
                
                $comment = '';
                foreach($issues as $issue_item) {
                    if($issue_item['code'] == 'words') $comment = $issue_item['comment'];
                }
                session(['bad_word_warning' => Lang::get('front/general.classified_'.$issue.'_issue', array('link' => $link, 'comment' => $comment))]);
            }
        }
        
        return $this;
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
                $classifieds_expire = Template::getSetting('classifieds_expire');
                $days = $classifieds_expire->diffInDays($now);
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

        Log::info('Classified ad setPaid: ' . $this->toJson());

        return $this->save();
    }
    
    public function unPaid($edit = false)
    {
        if($edit) {
            
        } else {
            //maybe email that ad was unpublished
        }
        $this->paid = 0;

        Log::info('Classified ad unPaid: ' . $this->toJson());

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
        $category_id = session('last_classifieds_category');
        if(!$category_id) $category_id = $this->categories()->lists('category_id')->first();
        
        try {
            return ClassifiedCategory::findOrFail($category_id);
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    
    public function last_category()
    {
        $category_id = $this->categories()->where('multicity', '=', 0)->lists('category_id')->last();

        try {
            return ClassifiedCategory::findOrFail($category_id);
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    
    public function categories_ids()
    {
        return $this->categories()->lists('category_id')->toArray();
    }
    
    public function deafult_categories_ids()
    {
        return $this->categories()->where('multicity', '=', 0)->lists('category_id')->toArray();
    }
    
    public function categories()
    {
        return $this->hasMany('App\ClassifiedCategoryValues', 'classified_id');
    }
    
    public function multicategory()
    {
        return $this->hasMany('App\ClassifiedMulticategory', 'classified_id');
    }
    
    public function classifiedfields()
    {
        return $this->hasMany('App\ClassifiedFields');
    }
    
    public function classifiedfieldsvalues()
    {
        return $this->hasMany('App\ClassifiedFieldsValues');
    }
    
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
	    
    public function reported()
    {
        return $this->hasMany('App\ReportedItems', 'item_id')->where('status', '=', 0);
    }
    
    public function isReported()
    {
        $max = Template::getSetting('max_reported');
        if($this->reported) {
            $reported = $this->reported->count();

            if($reported >= $max) return 1;
            else return 0;
        }
        
        return 0;
    }
    
    public function issues()
    {
        return $this->hasMany('App\Issues', 'item_id')->where('type', 'LIKE', $this->getType())->where('reviewed', '=', 0);
    }
    
    public function isActive($include_date = true, $cache = true)
    {
        /*$admin_approval = Template::getSetting('classified_approval');
        if($admin_approval && !$this->approved) return false;
        if(!$this->published) return false;
         
        if($this->isReported()) return false;
        if($this->issues()->count()) return false;*/
        
        //return $this->getActive()->where('id', '=', $this->id)->count();

        /*if($cache) {
            $minutes = Template::getCacheLifeTime();
            $active = Cache::store('file')->remember('is_active_classified['.$this->id.']', $minutes, function() use($include_date) {
                return $this->checkIsActive($include_date);
            });
        } else $active = $this->checkIsActive($include_date);
        
        return $active;*/
        
        return $this->checkIsActive($include_date);
    }
    
    public function checkIsActive($include_date = true)
    {
        $approval = (int)Template::getSetting('classified_approval');
        $max = Template::getSetting('max_reported');
        $now = Carbon::now();

        /*$query = Classified::where('id', '=', $this->id)->whereDoesntHave('issues', function($query){
            $query->where('reviewed', '=', '0');
        })->has('reported', '<', $max)->where(function($query) use ($approval)
        {
            if($approval) $query->where('approved', '=', 1);
        })->where('published', '=', 1)->where('paid', '=', 1)->where('active', '=', 1);

        if($include_date) $query->where('active_to', '>', $now);

        return $query->lists('id');*/
        if($this->published && $this->paid && $this->active && $this->active_to > $now && !$this->issues->count() && !$this->isReported()) {
            if($approval) {
                if($this->approved == 1) return true;
            } else return true;
        }
        
        return false;
    }
    
    public function getCost()
    {
        $tier = $this->countMulticity();
        $category = $this->last_category();
        $tier_price = $category->matchTierPrice($tier);

        if($tier_price) {
            $price = $tier_price;
        } else {
            $price = $category->getCost();
        }
        
        if($this->recurring_period_type) {
            $now = Carbon::now();
            $new_date = $this->getRecurringExpireDate();

            //$classifieds_expire = Template::getSetting('classifieds_expire');
            //$days_expire = $classifieds_expire->diffInDays($now);
            //$days_expire = $classifieds_expire->diffInDays($now);
            
            $reccuring_period = $new_date->diffInWeeks($now);
            $diff = round($reccuring_period / 2 * 100, 0) / 100;
            if($diff > 2 && $diff < 14) {
                $diff -= 1;
            }
            if($diff > 13) {
                $diff -= 2;
            }
            $new_price = $price * $diff;

            if($this->recurring_discount > 0) {
                $new_price = $new_price - ($new_price * ($this->recurring_discount/100));
            }
        } else $new_price = $price;
        
        return $new_price;
    }
    
    public function getMulticities()
    {
        $main_categories = ClassifiedCategory::getMainCategories()->lists('id')->toArray();

        //return $this->categories()->where('multicity', '=', 1)->whereIn('category_id', $main_categories)->groupBy('category_id');
        return $this->multicategory();
    }
    
    public function countMulticity()
    {
        $size = $this->getMulticities()->get()->count();

        return ++$size; //include main category
    }

    /**
     * Get active.
     *
     * @param bool $includeDate
     * @param bool $useCache
     *
     * @return mixed
     */
    public static function getActive($includeDate = true, $useCache = true)
    {
        $ids = CacheHelper::getActiveClassifieds($includeDate, $useCache);
        
        return Classified::whereIn('id', $ids);
    }

    /**
     * Get active classifieds.
     *
     * @param bool $includeDate
     *
     * @return mixed
     */
    public static function getActiveClassifieds($includeDate = true)
    {
        $approval = (int) Template::getSetting('classified_approval');
		$query = Classified::whereDoesntHave('issues', function ($query) {
                $query->where('reviewed', '=', '0');
            })
            ->has('reported', '<', Template::getSetting('max_reported'))
            ->where('published', '=', 1)
            ->where('paid', '=', 1)
            ->where('active', '=', 1)
        ;

        if ($approval) {
            $query->where('approved', '=', 1);
        }

		if ($includeDate) {
            $query->where('active_to', '>', Carbon::now());
        }
            
		return $query->lists('id');
    }
    
    public function images()
    {
        return $this->hasMany('App\Images', 'item_id')->where('type', 'LIKE', $this->getType())->whereNull('deleted_at');
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
            ->where('type', 'LIKE', 'classifieds')->orderBy('id');
                
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
        return Payments::getTransactionsHistory('classifieds', $this->id);       
    }
    
    public function recurringRenewal()
    {
        $this->setPaid(true, $this->renewal_cost);
        
        return true;
    }
    
    public static function reportsQuery()
    {
        return DB::table('classifieds as classified')
                ->join('users', 'users.id', '=', 'classified.user_id')
                ->select([
                    'classified.user_id',
                    'classified.title as classified_title',
                    DB::raw("CONCAT(users.first_name,'-',users.last_name) as fullname"),
                    'users.email as user_email',
                    'users.state as user_state',
                    'users.city as user_city',
                    DB::raw("(SELECT value from classified_fields_values WHERE classified_id = classified.id AND code = 'state') AS classified_state"),
                    DB::raw("(SELECT value from classified_fields_values WHERE classified_id = classified.id AND code = 'city') AS classified_city"),
                    DB::raw("(SELECT value from classified_fields_values WHERE classified_id = classified.id AND code = 'phone') AS classified_phone"),
                    DB::raw("(SELECT value from classified_fields_values WHERE classified_id = classified.id AND code = 'email') AS classified_email"),
                ])
                ->whereNull('classified.deleted_at');
    }
    
    public function getAdminAgreementTxt()
    {
        return HelloSignHelper::getAdminAgreementTxt($this->id, 'classifieds');
    }
    
    public function getRecurringExpireDate()
    {
        $new_date = Carbon::now();
        switch($this->recurring_period_type) {
            case('d'): $new_date->addDays($this->recurring_period); break;
            case('w'): $new_date->addDays($this->recurring_period * 7); break;
            case('m'): $new_date->addMonth($this->recurring_period); break;
            case('y'): $new_date->addMonth($this->recurring_period * 12); break;
        }
        
        return $new_date;
    }
}
