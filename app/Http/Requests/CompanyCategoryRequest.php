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
            "kiosk_image_top" => "required_with:sub_category_id",
            //"kiosk_image_top" => "required_if:category_id,!=,null",
          //  "kiosk_image_top" => "required_with:category_id|nullable", //The kiosk image top field is required when category id is present.
        ];
    }

    public function messages()
    {
        return [
            "category_id.required" => "The category field is required.",
            "site_id.required" => "The site field is required.",
            "kiosk_image_primary.required" => "The kiosk primary field is required.",
            "kiosk_image_primary.max" => "The kiosk primary may not be greater than 15 megabytes.",
            "kiosk_image_top.required_with" => "The kiosk top field is required when category is present.",
        ];
    }
}
