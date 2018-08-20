<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NearmeItemsCategory extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['deleted_at'];
	
	protected $fillable = ['title', 'position', 'published'];
	
    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'nearme_items_categories';

    protected $guarded = ['id'];

    public function nearmeItems()
    {
        return $this->hasMany('App\NearmeItems', 'category_id');
    }
    
    public static function getActive()
    {
        return NearmeItemsCategory::where('published', '=', '1')->orderBy('title');
    }

}