<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Nearme;
use Request;
use App\Http\Requests\NearmeRequest;
use File;

use App\Helpers\Template;
use Gregwar\Image\Image;

class NearmeItems extends Model {

    protected $table = 'nearme_items';
    
    protected $fillable = ['published', 'category_id', 'name', 'image', 'description', 'lab', 'thc', 'cbd', 'cbn', 'terpenes', 'ea', 'type_1g', 'type_2g', 'type_18', 'type_14', 'type_12', 'type_oz'];
    
    protected $guarded = ['id'];
    
    public function nearme()
    {
        return $this->belongsTo('App\Nearme', 'nearme');
    }
    
    public function category()
    {
        return $this->belongsTo('App\NearmeItemsCategory', 'category_id');
    }
    
    public function image()
    {
        if($this->image) {
            $image = Template::getNearMeImageDir().$this->nearme.'/'.$this->image;
        } else $image = asset('assets/images/nearme/item_placeholder.png');
        
        return $image;
    }
    
    public static function addItems(NearmeRequest $data, $nearme_id)
    {

        if($items = $data->all()) {
            foreach($items as $key => $param) {
                if($key == 'nearmeitems' && $nearme_id) {
                    NearmeItems::removeNearmeItems($nearme_id);
                    foreach($param as $item) {
                        $nearme_item = new NearmeItems($item);
                        $nearme_item->nearme = $nearme_id;
                        
                        if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $file = $item['image'];
                            if($file->isValid()) {
                                $filename = $file->getClientOriginalName();
                                $extension = $file->getClientOriginalExtension() ? : 'png';
                                $picture = str_random(10) . '.' . $extension;
                                
                                $folderName = Template::getTempImageDir();
            
                                $destinationPath = public_path() . $folderName;
                                $file->move($destinationPath, $picture);
                                
                                $image = NearmeItems::saveImage($nearme_id, $picture);
                                if($image) $nearme_item->image = $image;
                                
                            }
                        } elseif(isset($item['old_image']) && $item['old_image']) {
                            $image = NearmeItems::saveImage($nearme_id, $item['old_image']);
                            if($image) $nearme_item->image = $image;
                        }
                        
                        $nearme_item->save();
                    }
                }
            }
        }
    }
    
    public static function saveImage($nearme_id, $picture)
    {
        $max_width = (int)Template::getSetting('nearme_image_width');
        $max_height = (int)Template::getSetting('nearme_image_height');
        
        $folderName = Template::getTempImageDir();
        $path = realpath(public_path($folderName . $picture));

        if (File::exists($path)) {


            $folderName = Template::getNearMeImageDir().$nearme_id;
            if(!realpath(public_path($folderName))) {
                mkdir(public_path($folderName));
            }
            $destinationPath = realpath(public_path($folderName));
            $full_path = $destinationPath . DIRECTORY_SEPARATOR . $picture;
            rename($path, $full_path);
            
            list($width, $height) = getimagesize($full_path);

            Image::open($full_path)
                    ->cropResize($max_width, $max_height)
                    ->save($full_path);
        }
        
        return $picture;
    }
    
    public static function removeNearmeItems($nearme_id)
    {
        $affectedRows = NearmeItems::where('nearme', '=', $nearme_id)->delete();
        
        return $affectedRows;
    }
    
    public function getFrontendFillable()
    {
        return $this->fillable;
    }
    
    public static function getActive()
    {
        return NearmeItems::where('published', '=', 1); 
    }
}
