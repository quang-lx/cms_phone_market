<?php

namespace Modules\Admin\Http\Requests\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class UpdatepaymentMethodRequest extends FormRequest
{
    public function rules()
    {
        $payment_method = $this->route()->parameter('paymentmethod');
        return [
            'name' => "required|unique:payment_method,name,{$payment_method->id}",
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
