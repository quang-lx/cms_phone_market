<?php

namespace Modules\Admin\Http\Requests\User;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Mon\Entities\User;


class CreateUserRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $userType =$this->request->get('type');
        if ($userType == User::TYPE_USER) {
            $username =$this->request->get('username');
            $usernameFormatted = validate_isdn($username);
            $this->merge(['username' => $usernameFormatted]);
        }
    }
    public function rules()
    {
        $userType =$this->request->get('type');

        $rules = [


            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|regex:/^((?!.*[\s])(?=.*[a-zA-Z])(?=.*\d))/',
            'password_confirmation' => 'required|same:password'
        ];

        if ($userType == User::TYPE_USER) {
            $rules['username'] = ['required','unique:users', 'regex:/^[0-9]+$/'];
        }else {
            $rules['username'] = ['required',"unique:users,username",'regex:/^[\w-]*$/'];
            $rules['phone'] = ['required',"unique:users,phone", 'regex:/^[0-9]+$/'];

        }
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
            'username.regex' => 'Tài khoản không được chứa dấu cách/ ký tự đặc biệt/ tên có dấu',
            'username.unique' => 'Tài khoản đã tồn tại trên hệ thống',
            'name.required' => 'Tên là bắt buộc',
            'email.unique' => 'Email đã được sử dụng',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email sai định dạng',

            'phone.unique' => 'Số điện thoại đã được sử dụng',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.regex' => 'Số điện thoại chỉ dược nhập là số',


            'password.required' => 'Mật khẩu là bắt buộc',
            'password_confirmation.required' => 'Xác nhận mật khẩu không được để trống',
            'password.regex' => 'Mật khẩu phải bao gồm ký tự chữ và số, không được chứa dấu cách',
            'password_confirmation.same' => 'Xác nhận mật khẩu không đúng',
            'password.same' => 'Xác nhận mật khẩu không đúng',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự'
        ];
    }
}

