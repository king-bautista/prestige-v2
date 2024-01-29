<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyCategoryRequest extends FormRequest
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
            "category_id" => "required",
            "site_id" => "required",
            "kiosk_image_primary" => "required|mimes:jpeg,bmp,png|max:15240", 
        ];
    }

    public function messages()
    {
        return [
            "category_id.required" => "The category field is required.",
            "site_id.required" => "The site field is required.",
            "kiosk_image_primary.required" => "The kiosk image primary field is required.",
            "kiosk_image_primary.max" => "The kiosk image primary may not be greater than 15 megabytes.",
        ];
    }
}
