<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model {

    use SoftDeletes;
    use SluggableTrait;
  	use Taggable;

    protected $dates = ['deleted_at'];

    protected $table = 'pages';

    protected $fillable = ['title', 'url', 'meta_description', 'meta_keywords', 'content', 'published'];
    
    protected $guarded = ['id'];

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
