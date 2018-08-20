<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BlogCategoryRequest extends Request {

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
		return [
            'title' => 'required|min:3',
            'slug' => 'unique:blog_categories,slug,'.$this->get('id'),
		];
	}
    
    public function messages()
    {
        return [
            'slug.required' => 'The url field is required.',
            'slug.unique' => ' The url has already been taken.',
        ];
    }

}
