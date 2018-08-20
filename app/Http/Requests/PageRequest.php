<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PageRequest extends Request
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            'title' => 'required|min:3',
            'url' => 'required|unique:pages,url,'.$this->get('id'),
            'content' => 'required|min:3',
            'published' => 'required',
		];
	}

}
