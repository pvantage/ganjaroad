<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['deleted_at'];

    protected $table = 'blog_categories';
    
     protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $guarded = ['id'];

    public function blog()
    {
        return $this->hasMany('App\Blog');
    }

}