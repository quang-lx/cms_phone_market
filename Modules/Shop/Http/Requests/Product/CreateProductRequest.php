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
            'p_weight' => 'required',
            's_long' => 'required',
            's_width' => 'required',
            's_height' => 'required',
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
            'p_weight.required' => 'Cân nặng là bắt buộc',
            's_long.required' => 'Chiều dài đã được sử dụng',
            's_width.required' => 'Chiều rộng là bắt buộc',
            's_height.required' => 'Chiều cao sai định dạng',
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
