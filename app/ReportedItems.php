<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Helpers\Template;

class ReportedItems extends Model {

    protected $table = 'reported_items';

    protected $guarded = ['id'];
	
	public function item()
    {
        return $this->belongsTo('App\Classified', 'item_id');
    }
    
    public static function getAllReportedItems()
    {
        $max = Template::getSetting('max_reported');
        
        return ReportedItems::select(DB::raw('id, type, item_id, count(item_id) as items_count'))
                    ->where('status', '=', 0)
                    ->havingRaw("items_count >= ".$max)
                    ->groupBy('item_id');
    }
}
