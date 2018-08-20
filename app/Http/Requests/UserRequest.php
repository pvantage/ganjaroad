<?php

namespace App\Http\Requests;

use Sentinel;
use App\Helpers\Template;

class UserRequest extends Request
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
        $country = $this->input('country');
        $state = '';
        if($country == 'US') $state = 'required';

		$uid = 0;
        if(!$this->users) {
            $user = Sentinel::getUser();
			if($user) $uid = $user->id;
        } else $uid = $this->users->id;

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'first_name' => 'required|min:3',
                    'last_name' => 'required|min:3',
                    'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
                    'phone' => 'numeric',
                    'password' => 'required|between:3,32',
                    'password_confirm' => 'required|same:password',
                    'country' => 'required',
                    'state' => $state,
                    'city' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'first_name' => 'required|min:3',
                    'last_name' => 'required|min:3',
                    'email' => 'required|unique:users,email,' . $uid,
                    'phone' => 'numeric',
                    'password' => 'between:3,32',
                    'password_confirm' => 'between:3,32|same:password',
                    'pic_file' => 'mimes:jpg,jpeg,bmp,png|max:10000',
                    'country' => 'required',
                    'state' => $state,
                    'city' => 'required',
                    'dob' => 'date_format:'.Template::getDisplayedDateFormat(),
                ];
            }
            default:
                break;
        }

        return [

        ];
    }

    public function messages()
    {
        return [
            'phone.numeric' => 'Phone Number - Numbers only please'
        ];
    }
}
