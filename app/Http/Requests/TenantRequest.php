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
            'brand_id' => 'required|array|min:1',
            'site_id' => 'required|not_in:0',
            'site_building_id' => 'required|not_in:0',
            'site_building_level_id' => 'required|not_in:0',
            'company_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'brand_id.required' => 'Brand is required.',
            'site_id.required' => 'Site is required.',
            'site_building_id.required' => 'Building is required.',
            'site_building_level_id.required' => 'Floor is required.',
            'company_id.required' => 'Company is required.',

        ];
    
    }
}
