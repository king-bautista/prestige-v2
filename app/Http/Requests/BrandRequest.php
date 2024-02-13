<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            "descriptions" => "required|string",
            "category_id" => "required|numeric",
            "logo_hidden" => "required",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.required' => 'The category name field is required.',
            'logo_hidden.required' => 'The logo field is required.',
        ];
    }

}
