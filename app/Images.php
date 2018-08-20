<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use File;
use App\Helpers\Template;

class Images extends Model{

    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $table = 'images';

    protected $guarded  = array('id');
    
    public function nearme()
    {
        return $this->hasMany('App\ReportedItems', 'item_id')->where('type', 'LIKE', 'nearme');
    }
    
    public function classified()
    {
        return $this->hasMany('App\ReportedItems', 'item_id')->where('type', 'LIKE', 'classified');
    }
     
    public static function updateImages($type, $id, $images = array())
    {
        if(!$images || empty($images)) return false;
        if($type == 'classified') $limit = (int)Template::getSetting('classifieds_images_limit');
        else $limit = (int)Template::getSetting('nearme_images_limit');
        $images = array_slice($images, 0, $limit, true);

        $current_images = Images::where('item_id', '=', $id)->where('type', 'LIKE', $type)->get(['id', 'image']);
        $old_images = $new_images = $to_remove = array();
        foreach($current_images as $current) {
            if(!in_array($current->image, $images)) {
                $to_remove[$current->id] = $current->image;
            } else $old_images[$current->id] = $current->image;            
        }

        $new_images = array_diff($images, $old_images);
        if($to_remove) Images::deleteImages($to_remove);
        if($new_images) Images::addImages($type, $id, $new_images);
    }
    
    public static function deleteImages($images = array())
    {
        $ids = array_keys($images);
        $rows = Images::whereIn('id', $ids)->delete();
      
        //unlink?
        return $rows;
    }
    
    public static function addImages($type, $id, $images)
    {
        $path = public_path().Template::getTempImageDir();
        if($images && is_array($images)) {
            foreach($images as $image) {
                $new_path = public_path();
                if($type == 'nearme') {
                    $new_path .= Template::getNearMeImageDir();
                } elseif($type == 'classified') {
                    $new_path .= Template::getClassifiedsImageDir();
                } else continue;

                $new_path .= $id.'/';
                if (!file_exists($new_path)) {
                    mkdir($new_path, 0775, true);
                }

                if(file_exists($path.$image)) {
                    $img = new Images;
                    $img->item_id = $id;
                    $img->type = $type;
                    $img->image = $image;
                    $img->imagetype = pathinfo($path.$image, PATHINFO_EXTENSION);
                    $img->size = filesize($path.$image);
                    
                    $img->save();
                    
                    if($img->id) {
                        File::move($path.$image, $new_path.$image);
                    }
                }
            }
        }
    }
}