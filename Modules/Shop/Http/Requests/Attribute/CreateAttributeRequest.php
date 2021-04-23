<?php

namespace Modules\Shop\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class CreateAttributeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:attributes',
            'list_attribute_value' => 'required|array'

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
            'code.required' => 'Code không được để trống',
            'code.unique' => 'Code đã tồn tại',
            'list_attribute_value.required' => 'Thuộc tính không được để trống',


        ];
    
    }

    public function translationMessages()
    {
        return [];
    }
}
