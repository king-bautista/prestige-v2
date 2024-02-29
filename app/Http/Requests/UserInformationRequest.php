<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserInformationRequest extends FormRequest
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
            "first_name" => "required|string",
            "last_name" => "required|string",
            "mobile" => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11",
            // "password" => [
            //     'required',
            //     'string',
            //     'min:6',              // must be at least 6 characters in length
            //     'regex:/[a-z]/',      // must contain at least one lowercase letter
            //     'regex:/[A-Z]/',      // must contain at least one uppercase letter
            //     'regex:/[0-9]/',      // must contain at least one digit
            //     'regex:/[@$!%*#?&]/', // must contain a special character
            // ]
            'password' => [
                'required',
                'string',
                'min:6',
                'max:15',// must be at least 8 characters in length
            ],
            'password_confirmation' => 'required|same:password'
        ];
    }
}
