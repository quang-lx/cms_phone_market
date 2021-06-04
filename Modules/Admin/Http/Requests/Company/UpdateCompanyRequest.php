<?php

namespace Modules\Admin\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function rules()
    {
        $company = $this->route()->parameter('company');
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'email' => "required|unique:company,email,{$company->id}|email",
            'address' => 'required',
            'district_id' => 'required',
            'province_id' => 'required',
            'phoenix_id' => 'required',
            'description' => 'required'

        ];
        return $rules;
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
            'phone.required' => 'Số điện thoại là bắt buộc',
            'name.required' => 'Tên cửa hàng là bắt buộc',
            'email.unique' => 'Email đã được sử dụng',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email sai định dạng',
            'address.required' => 'Địa chỉ là bắt buộc',
            'district_id.required' => 'Quận huyện là bắt buộc',
            'province_id.required' => 'Tỉnh thành phố là bắt buộc',
            'phoenix_id.required' => 'Xã phường là bắt buộc',
            'description.required' => 'Mô tả là bắt buộc',

        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
