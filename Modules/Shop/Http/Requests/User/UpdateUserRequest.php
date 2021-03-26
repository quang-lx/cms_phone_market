<?php

namespace Modules\Shop\Http\Requests\User;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Modules\Mon\Entities\User;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route()->parameter('user');
        $userType =$this->request->get('type');


        $rules = [
            'name'=>"required",
            'email' => "required|unique:users,email,{$user->id}|email",
            'password_confirmation' => 'same:password'
        ];

        if ($userType == User::TYPE_USER) {
            $rules['username'] = ['required',"unique:users,username,{$user->id}", new PhoneNumber()];
        } else {
            $rules['username'] = ['required',"unique:users,username,{$user->id}"];
        }
        return $rules;
    }
    protected function prepareForValidation(): void
    {
        $userType =$this->request->get('type');
        if ($userType == User::TYPE_USER) {
            $username =$this->request->get('username');
            $usernameFormatted = validate_isdn($username);
            $this->merge(['username' => $usernameFormatted]);
        }
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
            'name.required' => 'Tên là bắt buộc',
            'email.unique' => 'Email đã được sử dung',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.confirmed' => 'Xác nhận mật khẩu không đúng',
            'password_confirmation.same' => 'Xác nhận mật khẩu không đúng',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email sai định dạng',
        ];
    }
}
