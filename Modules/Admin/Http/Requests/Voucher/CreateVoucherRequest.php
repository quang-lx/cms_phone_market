<?php

namespace Modules\Admin\Http\Requests\Voucher;

use Illuminate\Foundation\Http\FormRequest;

class CreateVoucherRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
            'code' => 'required|unique:vouchers',
            'discount_amount' => 'required',
            'require_min_amount' => 'required',
            'total' => 'required',
            'actived_at' => 'required',
            'expired_at' => 'required',
            'type' => 'required',
            'discount_type' => 'required',
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
            'title.required' => 'Tên chương trình là bắt buộc',
            'code.required' => 'Mã giảm giá là bắt buộc',
            'code.unique' => 'Mã giảm giá không được trùng lặp',
            'discount_amount.required' => 'Mức giảm là bắt buộc',
            'require_min_amount.required' => 'Giá trị đơn hàng tối thiểu là bắt buộc',
            'total.required' => 'Tổng số mã là bắt buộc',
            'actived_at.required' => 'Thời gian sử dụng mã là bắt buộc',
            'expired_at.required' => 'Thời gian sử dụng mã là bắt buộc',
            'type.required' => 'Loại mã là bắt buộc',
            'discount_type.required' => 'Loại giảm giá là bắt buộc',

        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
