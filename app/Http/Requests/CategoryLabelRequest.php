<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryLabelRequest extends FormRequest
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
            "company_id" => "required|numeric",
            "site_id" => "required|numeric",
            "label" => "required|string", 
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'company_id.required' => 'The company is required',
            'site_id.required' => 'The site field is required.',
            'label.required' => 'The label field is required.',
        ];
    }

}
