<?php

namespace Modules\Admin\Http\Requests\Pcategory;

use Illuminate\Foundation\Http\FormRequest;

class CreatePcategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'type' => 'required',
        ];
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
        return [
            'name.required' => 'Tên không được để trống',
            'type.required' => 'Kiểu không được để trống',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
