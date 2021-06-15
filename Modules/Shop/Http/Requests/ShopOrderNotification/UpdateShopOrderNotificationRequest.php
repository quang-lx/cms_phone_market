<?php

namespace Modules\Shop\Http\Requests\ShopOrderNotification;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopOrderNotificationRequest extends FormRequest
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
