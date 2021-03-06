<?php

namespace App\Container\Financial\src\Requests\Check;


use App\Container\Financial\src\Check;
use Illuminate\Foundation\Http\FormRequest;

class CheckRequest extends FormRequest
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
            'check'     => 'required|max:191',
            'pay_to'    => 'required|max:191',
            'status'    => 'required|numeric',
            'delivered_at'    => 'required_if:status,'.Check::DELIVERED,
        ];
    }

    public function messages()
    {
        return [];
    }
}