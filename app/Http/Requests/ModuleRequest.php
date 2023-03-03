<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
            "link" => "required|string",
            "class_name" => "required|string",
            "role" => "required|string",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'class_name.required' => 'The icon class name is required.',
        ];
    }

}
