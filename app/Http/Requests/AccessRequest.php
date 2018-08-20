<?php

namespace App\Http\Requests;

use App\Access;
use App\Http\Requests\Request;

class AccessRequest extends Request {

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
		$allowed = array_keys(Access::getAllowedTypes());
		return [
            'type' => 'required|in:'.implode(',', $allowed),
            'business' => 'required',
            'contact' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
		];
	}

}
