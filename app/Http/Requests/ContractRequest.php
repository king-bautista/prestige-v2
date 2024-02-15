<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
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
            "brands" => "required|array",
            "screens" => "required",
            "display_duration" => "required_if:is_indefinite,0|integer",
            "slots_per_loop" => "required|numeric",
            "exposure_per_day" => "required|numeric",
        ];
    }
}
