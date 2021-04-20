<?php

namespace Modules\Admin\Http\Requests\User;

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
            'password' => 'required|min:6',
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
            'password_confirmation.same' => 'Xác nhận mật khẩu không đúng',
            'password_confirmation.required' => 'Mật khẩu nhập lại là bắt buộc',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự'

        ];
    }
}
