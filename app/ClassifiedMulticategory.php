<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ClassifiedMulticategory extends Model {

    protected $table = 'classified_multicategory';
	public $timestamps = false;
    protected $fillable = ['classified_id', 'category_id'];
    protected $guarded = ['id'];

	
	public function classified()
    {
        return $this->belongsTo('App\Classified', 'classified_id');
    }
}
