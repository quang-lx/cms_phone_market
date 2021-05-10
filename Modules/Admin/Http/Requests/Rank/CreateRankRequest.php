<?php

namespace Modules\Admin\Http\Requests\Rank;

use Illuminate\Foundation\Http\FormRequest;

class CreateRankRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|unique:rank,name',
            'description' => 'required',
            'point' => 'required|integer',
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
            'name.required' => 'Mô tả là bắt buộc',
            'name.unique' => 'Tên đã tồn tại',
            'description.required' => 'Mô tả là bắt buộc',
            'point.required' => 'Mô tả là bắt buộc',
            'point.integer' => 'Điểm phải là số nguyên'
        ];
    }

    public function translationMessages()
    {
        return [
        ];
    }
}
