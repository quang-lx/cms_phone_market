<?php

namespace Modules\Shop\Http\Requests\User;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Mon\Entities\User;


class CreateUserRequest extends FormRequest
{

    public function rules()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'password_confirmation' => 'same:password',
            'username' => ['required',"unique:users,username",'regex:/^[\w-]*$/'],
            'phone' => 'required|unique:users,phone'
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
            'username.required' => 'Tài khoản là bắt buộc',
            'username.unique' => 'Tài khoản đã tồn tại trên hệ thống',
            'username.regex' => 'Tài khoản không được chứa dấu cách/ ký tự đặc biệt/ tên có dấu',
            'name.required' => 'Tên là bắt buộc',
            'email.unique' => 'Email đã được sử dụng',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email sai định dạng',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password_confirmation.same' => 'Xác nhận mật khẩu không đúng',
            'password.same' => 'Xác nhận mật khẩu không đúng',
            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'phone.required' => 'Số điện thoại là bắt buộc',
        ];
    }
}

