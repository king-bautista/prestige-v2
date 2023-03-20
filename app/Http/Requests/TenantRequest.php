<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
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
            "subscriber_logo" => "required_if:is_subscriber,1",
            "site_building_level_id" => "required|numeric",
            "site_building_id" => "required|numeric",
            "site_id" => "required|numeric",
            "brand_id" => "required|string",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "brand_id.required" => "Brand is required",
            "site_id.required" => "Site is required",
            "site_building_id.required" => "Building is required",
            "site_building_level_id.required" => "Floor is required",
            'subscriber_logo.required_if' => ':attribute field is required.',
        ];
    }

}
