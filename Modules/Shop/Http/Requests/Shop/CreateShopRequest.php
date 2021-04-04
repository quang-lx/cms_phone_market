<?php

namespace Modules\Shop\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required|min:9|numeric',
            'email' => 'email:rfc,dns'
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
            'name.required' => 'Tên chi nhánh không được để trống',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.min' => 'Số điện thoại không hợp lệ',
            'email.email' => 'Email không đúng định dạng',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
