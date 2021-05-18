<?php

namespace Modules\Admin\Http\Requests\paymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentMethodRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|unique:payment_method,name',
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
            'name.required' => 'Tên là bắt buộc',
            'name.unique' => 'Tên đã tồn tại trên hệ thống',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}