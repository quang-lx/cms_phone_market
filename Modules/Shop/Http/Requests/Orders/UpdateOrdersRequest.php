<?php

namespace Modules\Shop\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdersRequest extends FormRequest
{
    public function rules()
    {
        return [
            'price' => 'required|numeric',
            'numberDate' => 'required|numeric',
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
            'numberDate.required' => 'Số ngày là bắt buộc',
            'numberDate.numeric' => 'Số ngày phải là số',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
