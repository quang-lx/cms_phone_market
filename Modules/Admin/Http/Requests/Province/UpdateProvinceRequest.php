<?php

namespace Modules\Admin\Http\Requests\Province;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProvinceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|between:1,3',
            'type' => 'required',
            'phone_code' => 'required|between:1,10',
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
            'name.required' => 'Tên không được để trống',
            'code.required' => 'Mã đã tồn tại',
            'code.between' => 'Mã phải có độ dài từ 1 đến 10 ký tự',
            'type.required' => 'Kiểu không được để trống',
            'phone_code.required' => 'Phone code không được để trống',
            'phone_code.between' => 'Phone code phải có độ dài từ 1 đến 10 ký tự',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
