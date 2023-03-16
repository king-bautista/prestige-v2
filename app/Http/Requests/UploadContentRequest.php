<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadContentRequest extends FormRequest
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
            "site_id" => "required|not_in:0",
            "site_tenant_id" => "required|not_in:0",
            "screens" => "required",
            "start_date" => "required",
            "end_date" => "required",
            "display_duration" => "required|numeric",
        ];
    }
}
