<?php

namespace Modules\Admin\Http\Requests\Gift;

use Illuminate\Foundation\Http\FormRequest;

class CreateGiftRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:400',
            'point' => 'required|numeric',
            'amount' => 'required|numeric',
            'status' => 'required',
            'description' =>'required|max:4000',
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
            'name.required' => 'Tên quà tặng không được để trống',
            'name.max' => 'Tên quà tặng không được vượt quá 400 ký tự',
            'point.required' => 'Số điểm không được để trống',
            'point.numeric' => 'Số điểm phải là số',
            'amount.required' => 'Số lượng không được để trống',
            'amount.numeric' => 'Số lượng phải là số',
            'description.required' => 'Mô tả không được để trống',
            'description.max' => 'Mô tả không được vượt quá 4000 ký tự',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
