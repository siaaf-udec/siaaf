<?php

namespace App\Container\Financial\src\Requests\Approval;


use Illuminate\Foundation\Http\FormRequest;

class ApprovalExtensionRequest extends FormRequest
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
            'status'   => 'required|numeric',
            'date'     => 'required|date'
        ];
    }

    public function messages()
    {
        return [];
    }
}