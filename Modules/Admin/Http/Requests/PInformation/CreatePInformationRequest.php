<?php

namespace Modules\Admin\Http\Requests\PInformation;

use Illuminate\Foundation\Http\FormRequest;

class CreatePInformationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
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
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
