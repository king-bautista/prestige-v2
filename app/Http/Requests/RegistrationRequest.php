<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            "email" => "required|email|unique:admins",
            "password" => [
                'required',
                'string',
                'confirmed',   
                'min:6',              // must be at least 6 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            "password_confirmation" => "required|min:6",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'password.min' => ':attribute must be at least 6 characters in length.',
            'password.regex' => ':attribute must contain at least one number and both uppercase and lowercase letters and special character.',
        ];
    }
}
