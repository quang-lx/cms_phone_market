<?php

namespace Modules\Admin\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
{
    public function rules()
    {

        $rules = [


            'username' => 'required|unique:users,username',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'password_confirmation' => 'same:password'
        ];


        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'username' => 'Tài khoản'
        ];
    }
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'phone.required' => 'Số điện thoại là bắt buộc',
            'username.required' => 'Tài khoản là bắt buộc',
            'username.unique' => 'Tài khoản đã tồn tại trên hệ thống',
            'name.required' => 'Tên cửa hàng là bắt buộc',
            'email.unique' => 'Email đã được sử dụng',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email sai định dạng',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password_confirmation.same' => 'Xác nhận mật khẩu không đúng',
            'password.same' => 'Xác nhận mật khẩu không đúng',
        ];
    }
}
