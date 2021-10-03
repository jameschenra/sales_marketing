<?php

namespace App\Http\Requests\User;

use App\Helpers\PriceHelper;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ServiceRequest extends FormRequest
{
    public $defaultNameField;
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
        $defaultLang = $user->default_language ?: app()->getLocale();
        $this->defaultNameField = 'name_' . $defaultLang;
        $this->defaultDescField = 'description_' . $defaultLang;

        // Validator::extend('office_required', function ($attribute, $value, $parameters, $validator) {
        //     return count(array_filter($value, function ($var) use ($parameters) {
        //         return array_key_exists('office_id', $var);
        //     }));
        // });

        Validator::extend('check_office', function ($attribute, $value, $parameters, $validator) {
            return count(array_filter($value, function ($var) use ($parameters) {
                return array_key_exists('office_id', $var);
            }));
        });

        $request = request();

        $rules = [
            $this->defaultNameField => 'required|min:3',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            $this->defaultDescField => 'required|min:100|max:5000',
            'provide_online_type' => 'required',
            'online_delivery_time' => 'required_if:provide_online_type,==,1',
            'online_revision' => 'required_if:provide_online_type,==,1',
            'duration' => 'required_if:provide_online_type,==,2',
        ];

        if ($request->input('provide_online_type') == Service::PROVIDE_ONLINE_TYPE) {
            $rules['online_price'] = 'required|gt:0';
        }

        /* if (!$request->input('is_free_service')) {
            $rules['price'] = 'required|numeric|min:' . Service::MIN_PRICE;

            $price = $request->input('price');
            if ($price) {
                $discountMaxPrice = PriceHelper::getMaxServiceDiscountPrice((int) $price);
                $rules['discount_price'] = 'required|numeric|max:' . $discountMaxPrice . '|min:' . (Service::MIN_DISCOUNT_PRICE);
            }
        }*/

        return $rules;
    }

    public function messages()
    {
        return [
            $this->defaultNameField . '.required' => trans('main.Enter a service name'),
            $this->defaultNameField . '.min' => trans('main.The service name must contain at least 3 characters'),
            $this->defaultDescField . '.required' => trans('main.Enter a service description'),
            $this->defaultDescField . '.min' => trans('main.Description must be at least "00" characters', ['min' => 100]),
            $this->defaultDescField . '.max' => trans('main.Description must not exceed 5000 characters'),
            'category_id.required' => trans('main.Enter a service category'),
            'sub_category_id.required' => trans('main.Enter a specific sector'),
            'online_delivery_time.required_if' => trans('main.Enter delivery time'),
            'online_revision.required_if' => trans('main.Enter revision'),
            'online_price.required' => trans('main.Enter Pirce'),
            'office_info.check_office' => trans('main.Select-Office'),
            'duration.required' => trans('main.Enter duration of the service'),
            'duration.min' => trans('main.Duration of the service must be at least 15min'),
        ];
    }

    // public function validate()
    // {
    //     if (is_string($this->input('born_day')) && is_string($this->input('born_month')) && is_string($this->input('born_year')))
    //     {
    //         $bornDate = implode('-', $this->only(['born_year', 'born_month', 'born_day']));
    //         $this->merge([
    //             'born_date' => $bornDate,
    //         ]);
    //     }

    //     return parent::validate();
    // }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         if ($this->somethingElseIsInvalid()) {
    //             // $validator->errors()->add('field', 'Something is wrong with this field!');
    //         }
    //     });

    //     if ($validator->fails())
    //     {
    //     }
    // }

    // protected function prepareForValidation()
    // {
    //     $this->merge([
    //         'title' => fix_typos($this->title),
    //         'body' => filter_malicious_content($this->body),
    //         'tags' => convert_comma_separated_values_to_array($this->tags),
    //         'is_published' => (bool) $this->is_published,
    //     ]);
    // }
}
