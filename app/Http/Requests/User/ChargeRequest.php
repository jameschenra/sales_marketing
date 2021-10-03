<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChargeRequest extends FormRequest
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
            'amount' => 'required|numeric|min:10'
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => trans('main.balance.charge.validation.amount-required'),
            'amount.min' => trans('main.balance.charge.validation.amount-min'),
        ];
    }
}
