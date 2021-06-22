<?php

namespace Modules\Shop\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrdersRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'phone' => 'required',
            'customer_name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'price' => 'required',
            'created_at' => 'required',
            'payment_method' => 'required',
            'product_id' => 'required',
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
        $msg = [
            'phone.required' => 'Số điện thoại là bắt buộc',
            'customer_name.required' => 'Tên khách hàng là bắt buộc',
            'category_id.required' => 'Chuyên mục là bắt buộc',
            'brand_id.required' => 'Nhãn hiệu là bắt buộc',
            'price.required' => 'Giá là bắt buộc',
            'created_at.required' => 'Thời gian tiếp nhận là bắt buộc',
            'payment_method.required' => 'Hình thức thanh toán là bắt buộc',
            'product_id.required' => 'Sản phẩm là bắt buộc',

        ];
        return $msg;
    }

    public function translationMessages()
    {
        return [];
    }
}
