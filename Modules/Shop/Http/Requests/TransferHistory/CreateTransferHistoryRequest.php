<?php

namespace Modules\Shop\Http\Requests\TransferHistory;

use Illuminate\Foundation\Http\FormRequest;
use \App\Rules\CountProduct;

class CreateTransferHistoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'received_at' => 'required',
            'shop_id' => 'required',
            'products' => new CountProduct
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
