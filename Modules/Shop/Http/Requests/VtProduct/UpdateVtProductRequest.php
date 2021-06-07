<?php

namespace Modules\Shop\Http\Requests\VtProduct;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVtProductRequest extends FormRequest
{
    public function rules()
    {
        $vtproduct = $this->route()->parameter('vtproduct');
        return [
            'name' => "required|unique:vt_product,name,{$vtproduct->id}",
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
            'name.required' => 'Tên linh kiện là bắt buộc',
            'name.unique' => 'Tên linh kiện đã tồn tại',
            'price.required' => 'Giá link kiện/sp là bắt buộc',
            'price.numeric' => 'Giá link kiện/sp phải là số',
            'vt_category_id.required' => 'Danh mục linh kiện là bắt buộc',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
