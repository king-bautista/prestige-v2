<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortalCustomerCareRequest extends FormRequest
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
            "concern_id" => "required|string",
            "ticket_subject" => "required|string",
            "ticket_description" => "required|string",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'concern_id.required' => 'The concern menu is required',
        ];
    }
}
