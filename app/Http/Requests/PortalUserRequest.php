<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class PortalUserRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:admins',
            //'password' => 'required|confirmed|min:6',
            //'password' => 'nullable|required_with:password_confirmation|string|confirmed',
            // 'password' => 'required|confirmed',
            'password' => 'required|string',  
            
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'first_name.required' => 'First name is required!',
    //         'last_name.required' => 'Last name is required!',
    //         'email.required' => 'Email is required!',
    //         'password.required' => 'Password is required!', 
    //         'password.confirmed' => 'YOUR ERROR MESSAGE',
    //     ];
    // }
}
