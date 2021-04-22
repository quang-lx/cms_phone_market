<?php

namespace Modules\Admin\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
{
    public function rules()
    {
        $attributes = $this->route()->parameter('attribute');
        return [
            'name' => 'required',
            'code' => "required|unique:attributes,code,{$attributes->id}",
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
