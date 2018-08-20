<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;

class PlansCategory extends Model {

    protected $table = 'plans_categories';

    protected $guarded = ['id'];

    public function plan()
    {
        return $this->belongsTo('App\Plans');
    }
    
    public static function updateCategories($categories, $type, $plan_id)
    {
        PlansCategory::removeCategories($plan_id, $type);
        
        return PlansCategory::createCategories($categories, $type, $plan_id);
    }
    
    public static function removeCategories($plan_id, $type)
    {
        $affectedRows = PlansCategory::where('plan_id', '=', $plan_id)->where('type', 'LIKE', $type)->delete();
        
        return $affectedRows;
    }
    
    public static function createCategories($categories, $type, $plan_id)
    {
        if($type == 'classifieds' || $type == 'nearme' || $type == 'ads') {
            if($categories) {
                try{
                    foreach($categories as $category) {
                        $model = new PlansCategory();
                        $model->type = $type;
                        $model->plan_id = $plan_id;
                        $model->category_id = $category;
                        $model->save();
                    }
                } catch(\Exception $e){
                    Log::error($e->getMessage());
                    return false;
                }
            }
        }
                
        return true;
    }
}