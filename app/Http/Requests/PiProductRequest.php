<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PiProductRequest extends FormRequest
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
            "product_application" => "required",
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
            'ad_type.required' => 'The advertisement type field is required.',
        ];
    }
}
