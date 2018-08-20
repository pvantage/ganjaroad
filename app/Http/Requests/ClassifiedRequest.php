<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\ClassifiedCategory;
use Lang;
use Input;

use App\Helpers\Template;

class ClassifiedRequest extends Request {

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
        $map_address = $this->input('map_address');
        $address1 = $this->input('address1');
        $map_required = '';
        $address1_required = '';
        
        if($address1) {
            $address1_required = '|min:5';
        }elseif($map_address) {
            $map_required = '|min:5';
        }

        if(Request::is('admin/*')) {
            $rules = [
                'title' => 'required|min:3',
                'content' => 'required|min:3',
                'categories' => 'required|array',
                'map_address' => 'required_without:address1'.$map_required,
                'address1' => 'required_without:map_address'.$address1_required,
                'published' => 'required',
                'categories' => 'required|array',
                'lattitude' => 'numeric',
                'longitude' => 'numeric',
                'active_to' => 'date',
            ];
        } else {
            $rules = [
                'title' => 'required|min:3',
                'content' => 'required|min:3',
                'categories' => 'required|array',
                'map_address' => 'required_without:address1'.$map_required,
                'address1' => 'required_without:map_address'.$address1_required,
                'lattitude' => 'numeric',
                'longitude' => 'numeric',
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
        $validator->after(function($validator)
        {
            $error = false;
            $categories = $this->input('categories');

            if($categories) {
                foreach ($categories as $item) {
                    if (!(int)$item) {
                        $validator->errors()->add('categories', Lang::get('front/general.classified_category_select_all'));
                        $error = true;
                        break;
                    }
                }

                if(!$error) {
                    $flag = true;
                    foreach($categories as $category) {
                        $have_childrens = ClassifiedCategory::haveChildrens($category);
                        if(!$have_childrens) {
                            $flag = false;
                            break;
                        }
                    }
                    //$last_child = end($categories);
                    
                    if($flag) $validator->errors()->add('categories', Lang::get('front/general.classified_category_select_all'));
                }
            }
        });
        
    }

}
