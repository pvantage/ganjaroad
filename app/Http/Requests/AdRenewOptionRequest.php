<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AdRenewOptionRequest extends Request
{
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
        return [
            'renewal_period_name' => 'required',
            'renewal_time' => 'required|integer|min:1|max:35',
            'renewal_time_period' => 'required|in:d,w,m,y',
            'renewal_type' => 'required|in:nearme,classified,banner_ad',
            'renewal_discount' => 'required|integer|min:0|max:100'
        ];
    }
}
