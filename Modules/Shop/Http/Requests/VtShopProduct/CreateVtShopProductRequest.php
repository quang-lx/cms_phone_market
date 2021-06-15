<?php

namespace Modules\Shop\Http\Requests\VtShopProduct;

use Illuminate\Foundation\Http\FormRequest;

class CreateVtShopProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'vt_product_name' => 'required',
            'amount' => 'required|numeric|min:0',
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
            'vt_product_name.required' => 'Tên linh kiện là bắt buộc',
            'amount.required' => 'Số lượng linh kiện là bắt buộc',
            'amount.min' => 'Số lượng không được nhỏ hơn 0',
            'amount.numeric' => 'Số lượng phải là số',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
