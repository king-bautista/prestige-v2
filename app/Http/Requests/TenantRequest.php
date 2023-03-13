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
            "brand_id" => "required|not_in:0",
            "site_id" => "required|not_in:0",
            "site_building_id" => "required|not_in:0",
            "site_building_level_id" => "required|not_in:0",
        ];
    }
}
