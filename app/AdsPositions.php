<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdsPositions extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;
    
    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];
    
    protected $dates = ['deleted_at'];

    protected $table = 'ads_positions';

    protected $fillable = ['title', 'slug', 'height', 'width', 'published'];
    
    protected $guarded = ['id'];

    public function ads()
    {
        return $this->hasMany('App\Ads', 'position_id');
    }

}