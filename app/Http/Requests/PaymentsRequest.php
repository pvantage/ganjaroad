<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PaymentsRequest extends Request
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            'cc_number' => 'required|ccn',
            'cc_date' => 'required|ccd',
            'cc_cvc' => 'required|cvc',
		];
	}
}
