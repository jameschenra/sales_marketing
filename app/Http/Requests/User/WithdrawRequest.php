<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
        $user = Auth::user();

        return [
            'email' => ['required', 'email'],
            'withdraw_amount' => ['required', 'min:10', 'max:' . $user->wallet_balance, 'numeric'],
        ];
    }

    public function messages()
    {
        $user = Auth::user();

        return [
            'email.email' => trans('main.valid email'),
            'email.required' => trans('main.balance.withdraw.validation.email-required',
                ['provider-name' => 'PayPal']
            ),
            'withdraw-withdraw_amount.required' => trans('main.balance.withdraw.validation.amount-required'),
            'withdraw_amount.min' => trans('main.balance.withdraw.validation.amount-min'),
            'withdraw_amount.max' => trans('main.balance.withdraw.validation.amount-max',
                ['available-balance-value' => number_format($user->wallet_balance, 2)]
            ),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'withdraw_amount' => 'amount',
        ];
    }
}
