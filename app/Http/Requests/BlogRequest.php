<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Helpers\Template;

class BlogRequest extends Request {

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
            'content' => 'required|min:3',
			'blog_category_id' => 'required',
			'published' => 'required',
			'published_from' => 'date',
			'published_to' => 'date',
            'image' => 'image',
            'canonical' => 'url',
		];
	}

}
