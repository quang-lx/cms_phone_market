<?php

namespace Modules\Shop\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'p_weight' => 'numeric',
            's_long' => 'numeric',
            's_width' => 'numeric',
            's_height' => 'numeric',
            'brand_id' => 'required',
            'sku' => 'required|unique:product',
            'amount' => 'required',
            'price' => 'required',
            'category_id' => 'required',
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
            'name.required' => 'Tên sản phẩm là bắt buộc',
            'description.required' => 'Mô tả là bắt buộc',
            'p_weight.numeric' => 'Cân nặng yêu cầu nhập số',
            's_long.numeric' => 'Chiều dài yêu cầu nhập số',
            's_width.numeric' => 'Chiều rộng yêu cầu nhập số',
            's_height.numeric' => 'Chiều cao yêu cầu nhập số',
            'brand_id.required' => 'Nhãn hiệu là bắt buộc',
            'sku.required' => 'SKU là bắt buộc',
            'sku.unique' => 'SKU không được trùng lặp',
            'amount.required' => 'Số lượng là bắt buộc',
            'price.required' => 'Giá bán là bắt buộc',
            'category_id.required' => 'Danh mục là bắt buộc',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
