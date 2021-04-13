<?php

namespace Modules\Admin\Http\Requests\Banners;

use Illuminate\Foundation\Http\FormRequest;

class CreateBannersRequest extends FormRequest
{
    public function rules()
    {
        return [
            'description' => 'required',
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
            'description.required' => 'Mô tả là bắt buộc'
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
