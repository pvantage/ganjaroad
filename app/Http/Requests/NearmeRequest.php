<?php

namespace App\Http\Requests;

use App\Http\Requests\NearmeItemsRequest;
use Lang;
use Validator;
use App\Http\Requests\Request;

use App\Helpers\Template;

class NearmeRequest extends Request {

	public function authorize()
	{
		return true;
	}

	public function rules()
	{        
        if(Request::is('admin/*')) {
            $rules = [
                'title' => 'required|min:3',
                'content' => 'required|min:3',
                'category_id' => 'required',
                'hours' => 'array',
                'nearmeitems' => 'array',
            ];
        } else {
            $rules = [
                'category_id' => 'required|exists:nearme_categories,id',
                'title' => 'required|min:3',
                'content' => 'required|min:3',
                'url' => 'url',
                'email' => 'required|email',
                'phone' => 'required',
                'facebook' => 'url',
                'instagram' => 'url',
                'twitter' => 'url',
                'lattitude' => 'required',
                'longitude' => 'required',
                'country' => 'required',
                'city' => 'required',
                'zip' => 'required',
                'hours' => 'array',
                //'image' => 'image|mimes:jpg,jpeg,bmp,png|max:'.$file_size,
                'nearmeitems' => 'array',
            ];

            if($this->method() == 'POST' && Template::isDeveloper()) {
                $rules['recurring'] = 'required|in:1';
                $rules['renewal'] = 'required|exists:ad_renew_options,id';
            }
        }
        
        return $rules;
	}
    
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if (Request::hasFile('image')) {
            $file = Request::file('image');
            $picture = $this->saveTempImage($file);

            if($picture) Request::instance()->query->set('old_image', $picture);
        } elseif($picture = Request::get('old_image')) {
            Request::instance()->query->set('old_image', $picture);
        }
        
        if($items = Request::all()) {
            $nearmeitems = Request::get('nearmeitems');
            foreach($items as $key => $param) {
                if($key == 'nearmeitems') {
                    foreach($param as $i => $item) {
                        if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $file = $item['image'];
                            
                            $picture = $this->saveTempImage($file);
                            if($picture) {
                                $nearmeitems[$i]['image'] = $picture;
                            }
                        }
                    }
                }
            }
            
            Request::instance()->query->set('old_nearmeitems', $nearmeitems);
        }
        
        parent::failedValidation($validator);
    }
    
    public function saveTempImage($file)       
    {
        if($file->isValid()) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $allowed_extensions = array('jpg', 'jpeg', 'bmp', 'png');

            if(in_array($extension, $allowed_extensions)) {
                $folderName = Template::getTempImageDir();
                $picture = str_random(10) . '.' . $extension;

                $destinationPath = public_path() . $folderName;
                $full_path = $destinationPath.$picture;

                $file->move($destinationPath, $picture);  

                return $picture;
            }
        }
        
        return false;
    }
    
    public function moreValidation($validator)
    {
        $validator->after(function($validator) {
            $file_size = Template::getUploadFileMaxSize();
            $nearme_item_request = new NearmeItemsRequest;
            $rules = $nearme_item_request->rules();
            $messages = $nearme_item_request->messages();
            
            $img_max_size = Template::getUploadFileMaxSize();
            $image_rules = array(
                'image' => 'image|mimes:jpg,jpeg,bmp,png|max:'.$img_max_size,
            );
           
            $nearme_items = $this->input('nearmeitems');
            if(isset($nearme_items)) {
                foreach($nearme_items as $key => $item) {
                    $item = Request::only('nearmeitems.'.$key)['nearmeitems'][$key];
                    $custom_validator = Validator::make($item, $rules, $messages);
                    $errors = $validator->errors(); 
                    $errors->merge($custom_validator->errors());
                }
            }            

            if(Request::hasFile('image')) {
                $custom_validator = Validator::make(Request::only('image'), $image_rules, $messages);
                $errors = $validator->errors(); 
                $errors->merge($custom_validator->errors());
            }
            return $validator;
        });
    }

    public function messages()
    {
        return [
            'title.required' => 'Business Name is required.',
            'content.required' => ' Business Overview is required.',
        ];
    }
}
