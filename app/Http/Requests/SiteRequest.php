<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest
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
            "site_code" => "required|string",
            "descriptions" => "required|string",
            "site_logo_hidden" => "required",
            "site_banner_hidden" => "required",
            "site_background_hidden" => "required",
            "site_background_portrait_hidden" => "required",
        ];
    }
    public function messages()
    {
        return [
            'site_code.required' => 'The Code/Name field is required.',
            'site_logo_hidden.required' => 'The logo field is required.',
            'site_banner_hidden.required' => 'The banner image field is required.',
            'site_background_hidden.required' => 'The background image field is required.',
            'site_background_portrait_hidden.required' => 'The background portrait image field is required.',
        ];
    }
}
