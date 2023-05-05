<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkflowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this r   equest.
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
            "user_id" => "required|numeric",
            "permission_level" => "required|string",
            "condition" => "required|string",
            'user_id' => [
                "required",
                 Rule::unique('workflows')->where(function ($query) {
                     $query->where('user_id', $this->user_id)
                     ->where('permission_level', $this->permission_level)  
                     ->where('condition', $this->condition);
                        
                 })
            ],
        ];
    }

    public function messages() {
        return [
           'ip.unique' => 'Unique',
        ];
    }
}
