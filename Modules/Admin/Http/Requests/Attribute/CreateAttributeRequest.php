<?php

namespace Modules\Admin\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class CreateAttributeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required',
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
            'code.required' => 'Code không được để trống'

        ];
    
    }

    public function translationMessages()
    {
        return [];
    }
}
