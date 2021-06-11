<?php

namespace Modules\Shop\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => "required|unique:users,phone|regex:/^[0-9]+$/",
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
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.regex' => 'Số điện thoại chỉ dược nhập là số',
            'email.email' => 'Email không đúng định dạng',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
