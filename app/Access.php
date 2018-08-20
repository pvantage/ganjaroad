<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lang;

class Access extends Model {

    protected $table = 'access';

    protected $guarded = ['id'];
    protected $fillable = [
        'type',
        'business',
        'contact',
        'email',
        'phone',
        'address'
        
    ];
	
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public static function getAllowedTypes()
    {
        return array(
            'ads' => Lang::get('front/general.ads'),
            'nearme' => Lang::get('front/general.nearme'),
        );
    }
}
