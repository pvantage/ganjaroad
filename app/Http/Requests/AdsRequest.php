<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Validator;

use App\Helpers\Template;

class AdsRequest extends Request {

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
        if(Request::is('admin/*')) {
            $rules = [
                'title' => 'required|min:3',
                'phone' => 'numeric|digits_between:10,50',
                'url' => 'url|required|min:3',
                'position_id' => 'required|exists:ads_positions,id',
            ];
        } else {
            $rules = [
                'title' => 'required|min:3',
                'url' => 'url|required|min:3',
                'phone' => 'numeric|digits_between:10,50',
                'position_id' => 'required|exists:ads_positions,id',
                //'published_from' => 'required|date',
				//'published_to' => 'required|date'
            ];
            if($this->method() == 'POST' && Template::isDeveloper()) {
                $rules['recurring'] = 'required|in:1';
                $rules['renewal'] = 'required|exists:ad_renew_options,id';
            }
        }

        return $rules;
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
}
