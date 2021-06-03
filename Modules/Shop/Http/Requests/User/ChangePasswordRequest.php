<?php

namespace Modules\Shop\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route()->parameter('user');
        $rules = [
            'password' => 'required|min:6|regex:/^\S*$/u',
            'password_confirmation' => 'required|same:password'
        ];
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    public function messages()
    {
        return [
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.confirmed' => 'Xác nhận mật khẩu không đúng',
            'password.regex' => 'Mật khẩu không được chưa dấu cách',
            'password.min' => 'Mật khẩu phải lớn hơn 6 ký tự',
            'password_confirmation.same' => 'Xác nhận mật khẩu không đúng',
            'password_confirmation.required' => 'Xác nhận mật khẩu không được để trống',
        ];
    }
}
