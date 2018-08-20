<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Ads;
use App\Nearme;
use App\Classified;

class Plans extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['deleted_at'];

    protected $table = 'plans';

    protected $guarded = ['id'];
    
    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function classifiedcategory()
    {
        return $this->hasMany('App\PlansCategory', 'plan_id')->where('type', 'LIKE', 'classifieds');
    }
    
    public function nearmecategory()
    {
        return $this->hasMany('App\PlansCategory', 'plan_id')->where('type', 'LIKE', 'nearme');
    }
    
    public function adsposition()
    {
        return $this->hasMany('App\PlansCategory', 'plan_id')->where('type', 'LIKE', 'ads');
    }
    
    public function classifiedcategories()
    {
        return $this->classifiedcategory->lists('category_id')->all();
    }
    
    public function nearmecategories()
    {
        return $this->nearmecategory->lists('category_id')->all();
    }
    
    public function adspositions()
    {
        return $this->adsposition->lists('category_id')->all();
    }
    
    public static function getItemPaymentPlan($type, $item)
    {
        $plan = 0;
        switch($type) {
            case('ads'): 
                $plans = Plans::whereHas('adsposition', function($query) use ($item){
                    $query->where('category_id', '=', $item->position_id);
                });
                break;
                
            case('nearme'): 
                $plans = Plans::whereHas('nearmecategory', function($query) use ($item){
                    $query->where('category_id', '=', $item->category_id);
                });
                break;
            
            case('classifieds'):
                //plans not used for classifieds any more
                /*$category = $item->last_category();
                if($category) {
                    $plans = Plans::whereHas('classifiedcategory', function($query) use ($category){
                        $query->where('category_id', '=', $category->id);
                    });
                }*/
                break;
        }
        
        if(isset($plans)) {
            try{
                $plan = $plans->orderBy('priority')->firstOrFail();
                return $plan;
            } catch (ModelNotFoundException $e) {

            }
        }
        
        return $plan;
    }
    
    public static function getItemPrice($type, $item)
    {
        $price = $item->getCost();
        
        return $price;
    }
}
