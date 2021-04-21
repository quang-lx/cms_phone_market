<?php

namespace Modules\Shop\Http\Requests\Voucher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoucherRequest extends FormRequest
{
    public function rules()
    {
        return [];
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
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}
