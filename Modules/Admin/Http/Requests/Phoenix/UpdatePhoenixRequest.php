<?php

namespace Modules\Admin\Http\Requests\Phoenix;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhoenixRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'district_id' => 'required',
            'code' => 'required|between:1,3',
            'type' => 'required',
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
            'district_id.required' => 'Quận huyện không được để trống',
            'code.required' => 'Mã không được để trống',
            'code.between' => 'Mã phải có độ dài từ 1 đến 3 ký tự',
            'type.required' => 'Kiểu không được để trống',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
