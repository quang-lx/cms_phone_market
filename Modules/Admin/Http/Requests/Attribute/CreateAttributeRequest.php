<?php

namespace Modules\Admin\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;

class CreateAttributeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:attributes',
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

        ];
    
    }

    public function translationMessages()
    {
        return [];
    }
}
