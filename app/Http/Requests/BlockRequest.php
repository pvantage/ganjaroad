<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BlockRequest extends Request
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            'title' => 'required|min:3',
            'slug' => 'required|unique:static_blocks,slug,'.$this->get('id'),
            'published' => 'required',
		];
	}

}
