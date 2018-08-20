<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use App\TierPrices;

class ClassifiedSchema extends Model {

    protected $table = 'classified_schema';

    protected $guarded = ['id'];
    
    public function save(array $options = [])
    {
        parent::save();

        if($tiers = Request::get('tier')) {
            TierPrices::insertClassifiedsTierPrices($this->id, 'schema', $tiers);
        }
        
        return $this;
    }
    
    public function delete()
    {
        TierPrices::removeCategoryTiers($this->id, 'schema');
        
        return parent::delete();
    }
    
    public function tierPrices()
    {
        return $this->hasMany('App\TierPrices', 'category_id')->where('type', '=', 'schema');
    }
    
    public static function updatePositions($parent)
    {
        $collection = ClassifiedSchema::where('parent', '=', $parent)->orderBy('position', 'asc')->get();
        
        $counter = 0;
        foreach($collection as $item) {
            $category = ClassifiedSchema::find($item->id);
            $category->position = ++$counter;
            $category->save();
        }
    }

}