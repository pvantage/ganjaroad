<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PlansRequest extends Request
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            'title' => 'required|min:3',
            'published' => 'required',
            'priority' => 'required:integer',
            'amount' => 'required:number',
		];
	}

}
