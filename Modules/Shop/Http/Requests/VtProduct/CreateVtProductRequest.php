<?php

namespace Modules\Shop\Http\Requests\VtProduct;

use Illuminate\Foundation\Http\FormRequest;

class CreateVtProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required|numeric',
            'vt_category_id' => 'required',
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
            'name.required' => 'Tên là bắt buộc',
            'price.required' => 'Tiền là bắt buộc',
            'price.numeric' => 'Tiền phải là số',
            'vt_category_id.required' => 'Danh mục là bắt buộc',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
