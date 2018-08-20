<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassifiedFields extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'classified_fields';

    protected $guarded = ['id'];

    public function classified()
    {
        return $this->belongsTo('App\Classified');
    }

}