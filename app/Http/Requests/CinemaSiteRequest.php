<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CinemaSiteRequest extends FormRequest
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
            "site_id" => "required",
            "cinema_id" => "required",
        ];
    }

    public function messages()
    {
        return [
            "site_id.required" => "The site field is required.",
            "cinema_id.required" => "The cinema field is required.",
        ];
    }
}
