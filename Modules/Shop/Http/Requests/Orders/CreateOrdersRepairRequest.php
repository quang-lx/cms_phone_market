<?php

namespace Modules\Shop\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Validation\Validator;
use Modules\Mon\Entities\Product;

class CreateOrdersRepairRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'phone' => 'required',
            'customer_name' => 'required',
            'created_at' => 'required',
            'payment_method' => 'required',
            'products' => 'required',
            'type_product' => 'required',
        ];

        return $rules;
    }

    public function withValidator(Validator $validator)
    {
        $products = $this->products;
        $typeProduct = $this->type_product;
        $validator->after(function ($validator) use ($products, $typeProduct) {
            if (!count($products)){
                $validator->errors()->add('products', 'Thông tin sản phẩm không hợp lệ');
            }
            
            foreach ($products as $product){
                if ($typeProduct == Product::TYPE_PRODUCT){
                    if (!$product['amount']){
                        $validator->errors()->add('products', 'Vui lòng nhập số lượng sản phẩm');
                    }
    
                    if (!$product['problem_id']){
                        $validator->errors()->add('products', 'Vui lòng chọn vấn đề sửa chữa');
                    }
                } else {
                    if (!is_numeric($product['pay_price'])){
                        $validator->errors()->add('pay_price', 'Vui lòng nhập số tiền cần thanh toán');
                    }
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
            'type_product.required' => 'Sản phẩm là bắt buộc',

        ];
        return $msg;
    }

    public function translationMessages()
    {
        return [];
    }
}
