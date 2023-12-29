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
            "status_id" => "required",
            'status_id' => "required",
            'concern_id' => "required",
            'user_id' => "required",
            'first_name' => "required|string",
            'last_name' => "required|string",
            'ticket_subject' => "required|string",
            'ticket_description' => "required|string",
            'assigned_to_id' => "required",
            'internal_remark' => "required|string",
            'external_remark' => "required|string",
        ];
    }
}
