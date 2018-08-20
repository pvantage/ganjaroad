<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\CacheHelper;

class Menu extends Model
{
    protected $table = 'menu';
    protected $guarded = ['id'];
    protected $fillable = ['title', 'url', 'style', 'published', 'target', 'position', 'parent'];
    
    public function haveChildrens()
    {
        $count = CacheHelper::checkIfMenuHaveChildrens($this);

        return $count;
    }
    
    public static function updatePositions($parent)
    {
        $collection = Menu::where('parent', '=', $parent)->orderBy('position', 'asc')->get();
        
        $counter = 0;
        foreach($collection as $item) {
            $category = Menu::find($item->id);
            $category->position = ++$counter;
            $category->save();
        }
    }
    
    public static function getActiveByParent(Menu $parent)
    {
        return Menu::where('parent', '=', $parent->id)->where('published', '=', 1);
    }

}
