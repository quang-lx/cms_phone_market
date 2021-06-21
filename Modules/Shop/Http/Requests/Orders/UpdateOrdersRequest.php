<?php

namespace Modules\Shop\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdersRequest extends FormRequest
{
    public function rules()
    {
        return [
            'price' => 'required|numeric|min:0',
            'numberDate' => 'required|numeric|min:0',
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
            'price.required' => 'Giá là bắt buộc',
            'price.numeric' => 'Giá phải là số',
            'price.min' => 'Số tiền phải lớn hơn 0',
            'numberDate.required' => 'Số ngày là bắt buộc',
            'numberDate.numeric' => 'Số ngày phải là số',
            'numberDate.min' => 'Số ngày phải lớn hơn 0',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
