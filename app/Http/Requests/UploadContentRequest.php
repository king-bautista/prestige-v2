<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadContentRequest extends FormRequest
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
            "uom" => "required|not_in:0",
            "end_date" => "required",
            "start_date" => "required",
            "display_duration" => "required|numeric",
            "site_tenant_id" => "required|not_in:0",
            "site_id" => "required|not_in:0",
            // "screens"    => [
            //         'required',
            //         'array', // input must be an array
            //         'min:3'  // there must be three members in the array
            //     ],
            // "screens.*"  => [
            //         'required',
            //         'string',   // input must be of type string
            //         'distinct', // members of the array must be unique
            //         'min:3'     // each string must have min 3 chars
            //     ]
            ];
    }

    public function messages(): array
    {
        return [
            "site_id.required" => "Site is required",
            "site_tenant_id.required" => "Tenant is required",
            "start_date.required" => "Start date is required",
            "end_date.required" => "End date is required",
            "display_duration.required" => "Duration is required",
            "uom.required" => "Slots is required",
        ];
    }
}
