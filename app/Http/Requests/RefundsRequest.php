<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RefundsRequest extends Request
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
            'amount' => 'required|numeric',
            'transaction_id' => 'required|exists:payments,transaction_id',
		];
	}
}
