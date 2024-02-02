<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsRequest extends FormRequest
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
            "type" => "required|string",
            "image_url_hidden" => "required",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'image_url_hidden.required' => 'The banner image field is required.',
        ];
    }
}
