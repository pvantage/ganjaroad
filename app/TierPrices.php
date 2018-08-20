<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TierPrices extends Model {

    protected $table = 'tier_prices';
    protected $guarded = ['id'];
    protected $fillable = ['from', 'to', 'priority', 'price'];
	
    public static function insertClassifiedsTierPrices($category_id, $type = '', $tiers = array())
    {
		TierPrices::removeCategoryTiers($category_id, $type);
        if($tiers) {
            foreach($tiers as $tier) {
                $model = new TierPrices($tier);
                $model->category_id = $category_id;
                $model->type = $type;
                $model->save();
            }
        }
    }
	
	public static function removeCategoryTiers($category_id, $type)
    {
		$affectedRows = TierPrices::where('category_id', '=', $category_id)->where('type', '=', $type)->delete();
        if($affectedRows) {
            return true;
        }
        
        return false;
    }
}
