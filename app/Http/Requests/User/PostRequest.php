<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public $defaultTitleField;
    public $defaultContentField;
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
        $this->defaultTitleField = 'title_' . $defaultLang;
        $this->defaultContentField = 'content_' . $defaultLang;

        $rules = [
            $this->defaultTitleField => 'required|min:3',
            $this->defaultContentField => 'required|min:2000|max:5000',
            'category_id' => 'required|numeric',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'category_id.required' => trans('main.SELECT A CATEGORY'),
            $this->defaultTitleField . '.required' => trans('main.Enter post title'),
            $this->defaultTitleField . '.min' => trans('main.The title must contain at least 3 characters'),
            $this->defaultContentField . '.required' => trans('main.Enter post content'),
            $this->defaultContentField . '.min' => trans('main.Description must be at least "00" characters', ['min' => 2000]),
            $this->defaultContentField . '.max' => trans('main.Description must not exceed 5000 characters'),
        ];
    }
}
