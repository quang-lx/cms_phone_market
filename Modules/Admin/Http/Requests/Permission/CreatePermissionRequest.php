<?php

namespace Modules\Admin\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class CreatePermissionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'name' => 'required',
            'group' => 'required',
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
            'title.required' => 'Tiêu đề là bắt buộc',
            'group.required' => 'Group là bắt buộc',
            'name.required' => 'Name là bắt buộc',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
