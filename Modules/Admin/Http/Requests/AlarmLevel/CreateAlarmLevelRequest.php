<?php

namespace Modules\Admin\Http\Requests\AlarmLevel;

use Illuminate\Foundation\Http\FormRequest;

class CreateAlarmLevelRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required'
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
            'name.required' => 'Tên là bắt buộc'
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
