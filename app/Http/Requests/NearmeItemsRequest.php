<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Lang;

use App\Helpers\Template;

class NearmeItemsRequest extends Request {

	public function authorize()
	{
		return true;
	}


	public function rules()
	{
        $img_max_size = Template::getUploadFileMaxSize();
        if(Request::is('admin/*')) {
            return [
                'category_id' => 'required|exists:nearme_items_categories,id',
                'published' => 'boolean',
                'name' => 'required|min:2',
                'image' => 'required_without:old_image|image'
            ];
        } else {
            return [
                'category_id' => 'required|exists:nearme_items_categories,id',
                'published' => 'boolean',
                'name' => 'required|min:2',
                'image' => 'required_without:old_image|image|max:'.$img_max_size,
            ];
        }
	}
    
    public function moreValidation($validator)
    {
        $validator->after(function($validator) {
            $img_max_size = Template::getUploadFileMaxSize();
            $rules = array(
                'image' => 'image|mimes:jpg,jpeg,bmp,png|max:'.$img_max_size,
            );

            if(Request::hasFile('image')) {
                $custom_validator = Validator::make(Request::only('image'), $rules);
                $errors = $validator->errors();
                $errors->merge($custom_validator->errors()); 
            }
            return $validator;
        });
    }
    
    public function messages()
    {
        return [
            'image.required' => 'Menu Item Image Required',
            'image.required_without' => 'Menu Item Image Required',
            'image.image' => 'The menu item image must be an image.',
            'image.max' => 'The menu item image size may not be greater than :max kilobytes.',
            'name.required' => 'Enter Menu Item Name',
        ];
    }
}
