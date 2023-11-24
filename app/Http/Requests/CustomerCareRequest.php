<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerCareRequest extends FormRequest
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
            "assigned_to_alias" => "required|string",
            "assigned_to_id" => "required|string",
            "status_id" => "required",
            "ticket_description" => "required|string",
            "ticket_subject" => "required|string",
            "last_name" => "required|string",
            "first_name" => "required|string",
            "user_id" => "required",
            "concern_id" => "required",
        ];
    }
}
