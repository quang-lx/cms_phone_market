<?php

namespace Modules\Admin\Http\Requests\Warning;

use Illuminate\Foundation\Http\FormRequest;

class RejectWarningRequest extends FormRequest
{
    public function rules()
    {
        return [
            'reject_reason' => 'required',
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
            'reject_reason.required' => 'Lý do là bắt buộc',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
