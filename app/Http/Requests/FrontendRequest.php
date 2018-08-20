<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Validator;
use App\Helpers\Template;

class FrontendRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
            case 'PUT':
                return [
                    'dob' => 'date_format:'.Template::getDisplayedDateFormat(),
                ];
            case 'PATCH':
            default:
                break;
        }

        return [

        ];
    }

    public function moreValidation($validator)
    {
        $validator->after(function($validator) {
            $img_max_size = Template::getUploadFileMaxSize();
            $rules = array(
                'pic' => 'image|mimes:jpg,jpeg,bmp,png|max:'.$img_max_size,
            );

            $messages = $this->messages();
            if(Request::hasFile('pic')) {
                $custom_validator = Validator::make(Request::only('pic'), $rules, $messages);
                $errors = $validator->errors();
                $errors->merge($custom_validator->errors()); 
            }
            return $validator;
        });
    }

	/*
    public function messages()
    {
        return [
            'pic.image' => 'The image must be an image.',
            'pic.mimes' => 'The image must be a file of type: :values.',
            'pic.max' => 'The image size may not be greater than :max kilobytes.',
        ];
    }
	*/
}

