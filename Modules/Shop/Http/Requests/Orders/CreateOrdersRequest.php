<?php

namespace Modules\Shop\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Validation\Validator;

class CreateOrdersRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'phone' => 'required',
            'customer_name' => 'required',
            'created_at' => 'required',
            'payment_method' => 'required',
            'products' => 'required'
        ];

        return $rules;
    }

    public function withValidator(Validator $validator)
    {
        $products = $this->products;
        $validator->after(function ($validator) use ($products) {
            if (!count($products)){
                $validator->errors()->add('products', 'Thông tin sản phẩm không hợp lệ');
            }
            
            foreach ($products as $product){
                if (!$product['amount']){
                    $validator->errors()->add('products', 'Thông tin sản phẩm không hợp lệ');
                }
                
            }

        });
        
        return $validator;                     
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
            'created_at.required' => 'Thời gian tiếp nhận là bắt buộc',
            'payment_method.required' => 'Hình thức thanh toán là bắt buộc',
            'products.required' => 'Sản phẩm là bắt buộc',

        ];
        return $msg;
    }

    public function translationMessages()
    {
        return [];
    }
}
