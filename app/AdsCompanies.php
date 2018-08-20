<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdsCompanies extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'ads_companies';

    protected $fillable = ['title', 'notes'];
    
    protected $guarded = ['id'];

    public function ads()
    {
        return $this->hasMany('App\Ads', 'company');
    }

}