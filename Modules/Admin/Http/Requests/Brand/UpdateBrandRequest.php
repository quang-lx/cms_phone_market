<?php

namespace Modules\Admin\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'type' => 'required',
            'category_id' => 'required',
            'status' => 'required',
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
            'name.required' => 'Tên không được để trống',
            'type.required' => 'Kiểu không được để trống',
            'category_id.required' => 'Danh mục không được để trống',
            'status.required' => 'Trạng thái không được để trống',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
