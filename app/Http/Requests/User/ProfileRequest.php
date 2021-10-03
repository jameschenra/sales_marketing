<?php

namespace App\Http\Requests\User;

use App\Enums\UserType;
use App\Models\EnrollType;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public $defaultDescField;
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
        $user = auth()->user();
        $userDetail = $user->detail;
        $defaultLang = $user->default_language ?: app()->getLocale();
        $this->defaultDescField = 'description_' . $defaultLang;

        if (request()->input('profile_save') == 'profile-wizard') {
            $rules = [
                'country_id' => 'required',
            ];
        } else {
            $rules = [
                'name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'country_id' => 'required',
            ];
        }

        if ($user->type == UserType::SELLER) {
            $rules = array_merge($rules, [
                'language' => 'required',
                'professions' => 'required',
                'enroll_type' => 'required',
                $this->defaultDescField => 'required|min:300|max:6000',
            ]);

            if (request()->input('enroll_type') != EnrollType::NOT_ENROLLED) {
                $rules = array_merge($rules, [
                    'association_id' => 'required',
                    'city' => 'required',
                ]);
            }
        }

        if (!$userDetail->photo) {
            $rules['photo'] = 'required';
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => trans('main.Enter your name'),
            'last_name.required' => trans('main.Enter your surname'),
            'email.required' => trans('main.Enter your email'),
            'country.required' => trans('main.Select your country'),
            'language.required' => trans('main.Select your languages spoken'),
            $this->defaultDescField . '.required' => trans('main.Enter profile information'),
            $this->defaultDescField . '.min' => trans('main.Description must be at least 300 characters'),
            $this->defaultDescField . '.max' => trans('main.Description must not exceed 5000 characters'),
        ];
    }
}
