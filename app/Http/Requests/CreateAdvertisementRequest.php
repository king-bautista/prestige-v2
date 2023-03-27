<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdvertisementRequest extends FormRequest
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
            'display_duration' => 'required|numeric',
            //'file_type' => 'required|image|mimes:jpeg,jpg',//'required|image|mimes:png,jpeg,jpg',//'required|mimes:png,jpg,jpeg|max:2048',
            //'image' => 'required|image|mimes:png,jpeg,jpg',
            'ad_type' => 'required|string',
            'name' => 'required',
            'brand_id' => 'required|string',
            'company_id' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            "brand_id.required" => "Brand is required",
            "company_id.required" => "Company is required",
            "name.required" => "Name is required",
            "ad_type.required" => "Ad Type is required",
            "display_duration.required" => "Duration is required",
        ];
    }
}
