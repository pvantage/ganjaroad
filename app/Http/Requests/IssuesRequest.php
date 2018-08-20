<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IssuesRequest extends Request
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            'item_id' => 'required|integer',
		];
	}

}
