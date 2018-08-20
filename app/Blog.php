<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Blog extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;
  	use Taggable;

    protected $dates = ['deleted_at'];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'blogs';
    
    protected $fillable = ['blog_category_id', 'title', 'slug', 'content', 'canonical', 'meta_description', 'meta_keywords', 'published', 'published_from', 'published_to'];

    protected $guarded = ['id'];

    public function comments()
    {
        return $this->hasMany('App\BlogComment');
    }
    public function category()
    {
        return $this->belongsTo('App\BlogCategory', 'blog_category_id');
    }
    
    public function category_title()
    {
        $category = $this->category;
        return $category->title;
    }
    
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    protected function increment($column, $amount = 1, array $extra = [])
    {
        $this->timestamps = false;
        $increment = $this->incrementOrDecrement($column, $amount, $extra, 'increment');
        $this->timestamps = true;
        
        return $increment;
    }
    
    public function getBlogcategoryAttribute()
    {
        return $this->category->lists('id');
    }
    
    // 0 - unpublished
    // 1 - active
    // 2 - expired
    public function getAdminStatus()
    {
        if(!$this->published) return 0; 
        elseif($this->isActive()) return 1;
        else return 2;
    }
    
    public function isActive()
    {
        return $this->where(function($query)
        {
            $query->where('published_from', '<=', Carbon::now());
            $query->orWhereNull('published_from');
        })->where(function($query)
        {
            $query->where('published_to', '>', Carbon::now());
            $query->orWhereNull('published_to');
        })->where('published', '=', 1)->where('id', '=', $this->id)->count();
    }
    
    public static function getActiveBlogs()
    {
        return Blog::where(function($query)
        {
            $query->where('published_from', '<=', Carbon::now());
            $query->orWhereNull('published_from');
        })->where(function($query)
        {
            $query->where('published_to', '>', Carbon::now());
            $query->orWhereNull('published_to');
        })->where('published', '=', 1)->groupBy('id');
    }
    
}
