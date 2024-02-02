<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            "event_name" => "required|string",
            "event_date" => "required|string",
            "image_url" => "required",
        ];
    }

    public function messages()
    {
        return [
            "site_id.required" => "The site field is required.",
            "event_name.required" => "The event field is required.",
            "image_url.required" => "The banner field is required.",
        ];
    }
}
