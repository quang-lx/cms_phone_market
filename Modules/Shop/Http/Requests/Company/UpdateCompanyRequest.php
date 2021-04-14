<?php

namespace Modules\Shop\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function rules()
    {
      return [
            'username' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
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
            'username.required' => 'Tên là bắt buộc',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'address.required' => 'Địa chỉ là bắt buộc',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
