<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementRequest extends FormRequest
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
            "name" => "required|string",
            "company_id" => "required|string",
            "contract_id" => "required|string",
            "brand_id" => "required|string",
            // "status_id" => "required|string",
            "display_duration" => "required|numeric|min:0|not_in:0",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'company_id.required' => 'The company field is required',
            'contract_id.required' => 'The contract field is required.',
            'brand_id.required' => 'The brand field is required.',
            // 'status_id.required' => 'The status field is required.',
        ];
    }
}
