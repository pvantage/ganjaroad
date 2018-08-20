<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ClassifiedFieldsRequest extends Request {

	public function authorize()
	{
		return true;
	}


	public function rules()
	{
		return [
            'title' => 'required|min:3',
            'code' => 'required|min:3|unique:classified_fields',
		];
	}
}
