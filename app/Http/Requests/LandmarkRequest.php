<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LandmarkRequest extends FormRequest
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
            "landmark" => "required|string",
            "descriptions" => "required|string",
            "imgBanner_hidden" => "required",
            "imgBannerThumbnail_hidden" => "required",

        ];
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'site_id.required' => 'The site field is required.',
            'imgBanner_hidden.required' => 'The banner field is required.',
            'imgBannerThumbnail_hidden.required' => 'The Banner Thumbnail field is required.',
        ];
    }
}
