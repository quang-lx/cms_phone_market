<?php

namespace Modules\Admin\Http\Requests\District;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDistrictRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'province_id' => 'required',
            'lat' => 'required',
            'lng' => 'required', 
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
            'province_id.required' => 'Thành phố không được để trống',
            'lat.required' => 'lat không được để trống',
            'lng.required' => 'lng không được để trống',
            'code.required' => 'Mã không được để trống',
            'code.between' => 'Mã phải có độ dài từ 1 đến 10 ký tự',
            'type.required' => 'Kiểu không được để trống',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
