<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteScreenProductRequest extends FormRequest
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
            "site_screen_id" => "required",
            "ad_type" => "required|array",
            "width" => "required|not_in:0",
            "height" => "required|not_in:0",
            "sec_slot" => "required|not_in:0",
            "slots" => "required|not_in:0",
        ];
    }

    
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'site_screen_id.required' => 'The site screen is required.',
            'ad_type.required' => 'The advertisement type field is required.',
        ];
    }
}
