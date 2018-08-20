<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HelloSignItems extends Model {

    protected $table = 'hellosign_items';
    protected $guarded = ['id'];
    protected $fillable = ['hellosign_id', 'item_id', 'item_type'];
    public $timestamps = false;
	
	public function agreement()
	{
		return $this->belongsTo('App\HelloSign', 'hellosign_id');
	}
}
