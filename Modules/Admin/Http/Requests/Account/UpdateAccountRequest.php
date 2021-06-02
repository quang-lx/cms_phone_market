<?php

namespace Modules\Admin\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function rules()
    {
        return [
            'rank_point' => 'required',
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
            'rank_point.required' => 'Điểm không được để trống',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
