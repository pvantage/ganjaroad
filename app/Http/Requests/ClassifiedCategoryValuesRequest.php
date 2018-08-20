<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ClassifiedCategoryValuesRequest extends Request {

	public function authorize()
	{
		return true;
	}


	public function rules()
	{
		return [
            'category_id' => 'integer',
            'classified_id' => 'integer',
		];
	}
}
