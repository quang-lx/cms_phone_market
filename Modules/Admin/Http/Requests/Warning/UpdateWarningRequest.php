<?php

namespace Modules\Admin\Http\Requests\Warning;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarningRequest extends FormRequest
{
    public function rules()
    {
        $rules =  [
            'level_id' => 'required',
            'place' => 'required',
            'type' => 'required',
            'type_id' => 'required'
        ];
        if ($this->request->get('type') == 'text') {
            $rules['content'] = 'required';
        }
        return $rules;
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
            'level_id.required' => 'Mức độ là bắt buộc',
            'content.required' => 'Chi tiết cảnh báo là bắt buộc',
            'place.required' => 'Địa điểm là bắt buộc',
            'type_id.required' => 'Loại cảnh báo là bắt buộc',
            'type.required' => 'Loại nội dung là bắt buộc',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
