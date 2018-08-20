<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AdsPositionsRequest extends Request {

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            'title' => 'required|min:3',
            'slug' => 'required|unique:ads_positions,slug,'.$this->get('id'),
            'height' => 'required|integer',
            'width' => 'required|integer',
		];
	}

}
