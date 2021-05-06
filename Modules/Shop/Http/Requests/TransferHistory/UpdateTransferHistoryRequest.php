<?php

namespace Modules\Shop\Http\Requests\TransferHistory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransferHistoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'received_at' => 'required',
            'shop_id' => 'required',
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
            'title.required' => 'Tên đơn là bắt buộc',
            'received_at.required' => 'Thời gian chuyển là bắt buộc',
            'shop_id.required' => 'Kho nhận là bắt buộc',
            
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
