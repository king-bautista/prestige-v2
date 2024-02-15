<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            //"email" => "required|string",
            //"address" => "required|string",
            //"contact_number" => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11",
            "tin" => "nullable|numeric",
            "classification_id" => "required|numeric",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The company name field is required.',
            'classification_id.required' => 'The classification field is required.',
        ];
    }

}
