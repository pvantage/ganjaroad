<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Helpers\Template;

class NearmeCategory extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['deleted_at'];
    
    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $table = 'nearme_categories';

    protected $guarded = ['id'];

    public function nearme()
    {
        return $this->hasMany('App\Nearme', 'category_id');
    }

    public function getIcon()
    {
        if($icon = $this->icon) {
            return Template::getNearMeCategoryImageDir().$icon;
        } else return Template::getSetting('nearme_default_icon');
    }
}