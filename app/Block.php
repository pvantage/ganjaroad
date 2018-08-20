<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'static_blocks';
    
    protected $fillable = ['title', 'slug', 'content', 'published'];

    protected $guarded = ['id'];

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
