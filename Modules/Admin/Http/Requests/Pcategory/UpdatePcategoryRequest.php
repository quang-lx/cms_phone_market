<?php

namespace Modules\Admin\Http\Requests\Pcategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePcategoryRequest extends FormRequest
{
    public function rules()
    {
        return [];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}
