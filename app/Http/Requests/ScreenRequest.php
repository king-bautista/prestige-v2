<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScreenRequest extends FormRequest
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
            "site_id" => "required",
            "site_building_id" => "required",
            "site_building_level_id" => "required|not_in:0",
            "screen_type" => "required",
            "orientation" => "required",
            "product_application" => "required",
            "slots" => "required|not_in:0|numeric",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'site_id.required' => 'The site field is required.',
            'site_building_id.required' => 'The building field is required.',
            'site_building_level_id.required' => 'The floor field is required.',
        ];
    }
}
