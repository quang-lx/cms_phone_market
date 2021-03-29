<?php

namespace Modules\Api\Http\Requests;

class LoginSocialRequest extends RequestAPI
{
    public function rules()
    {
        return [
            'token' => 'required',
            'provider' => 'required',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Họ tên',
            'birth_date' => 'Ngày sinh',
        ];
    }


}
