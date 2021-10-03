<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class OfficeRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'phone_number' => 'required',
            'country_id' => 'required',
            'region_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => trans('main.Enter office name'),
            'phone_number.required' => trans('main.Enter office telephone'),
            'phone_number.phone' => trans('main.Enter a telephone number'),
            'country_id.required' => trans('main.Select office country'),
            'region_id.required' => trans('main.Enter office region'),
            'city_id.required' => trans('main.Enter office city'),
            'address.required' => trans('main.Enter office address'),
            'zip_code.required' => trans('main.Zip Code error message'),
        ];
    }
}
