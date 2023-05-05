<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementRequest extends FormRequest
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
            "company_id" => "required|string",
            "contract_id" => "required|string",
            "brand_id" => "required|string",
            "product_application" => "required|string",
            "screen_ids" => "required",
            "name" => "required|string",
            "display_duration" => "required|numeric",
            "banner_portrait" => "required_without_all:banner_landscape,fullscreen_portrait,fullscreen_landscape",
            "banner_landscape" => "required_without_all:banner_portrait,fullscreen_portrait,fullscreen_landscape",
            "fullscreen_portrait" => "required_without_all:banner_portrait,banner_landscape,fullscreen_landscape",
            "fullscreen_landscape" => "required_without_all:banner_portrait,banner_landscape,fullscreen_portrait",
            "status_id" => "required|string",
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'company_id.required' => 'The company field is required',
            'company_id.required' => 'The company field is required',
            'contract_id.required' => 'The contract field is required.',
            'brand_id.required' => 'The brand field is required.',
            'screen_ids.required' => 'The screens field is required.',
            'banner_portrait.required_without_all' => 'You need to upload atleast 1 material.',
            'banner_landscape.required_without_all' => 'You need to upload atleast 1 material.',
            'fullscreen_portrait.required_without_all' => 'You need to upload atleast 1 material.',
            'fullscreen_landscape.required_without_all' => 'You need to upload atleast 1 material.',
            'status_id.required' => 'The status field is required.',
        ];
    }
}
