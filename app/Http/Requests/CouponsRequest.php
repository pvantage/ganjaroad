<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Validator;
use App\Helpers\Template;

class CouponsRequest extends Request {

	public function authorize()
	{
		return true;
	}


	public function rules()
	{
		return [
			'code' => 'required|unique:coupons,code,'.$this->get('id'),
            'discount' => 'numeric',
            'max_amount' => 'numeric',
			'uses_per_coupon' => 'required|integer',
            'uses_per_user' => 'required|integer',
			'start_date' => 'date',
			'end_date' => 'date',
		];
	}
}
