<?php

namespace Modules\Shop\Http\Requests\Voucher;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Validation\Validator;

class UpdateVoucherRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->route('voucher')->id; // lấy id bài post muốn update
        return [
            'title' => 'required',
            'code' => 'required|unique:vouchers,id,{$id}|regex:/^[A-Za-z0-9]*$/',
            'discount_amount' => 'required',
            'require_min_amount' => 'required',
            'total' => 'required',
            'actived_at' => 'required',
            'expired_at' => 'required',
            'type' => 'required',
            'discount_type' => 'required',
            'use_condition' => 'required',
            'description' => 'required',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $start = strtotime($this->actived_at);
        $end = strtotime($this->expired_at);

        $validator->after(function ($validator) use ($start, $end) {
            if ($start < time()){
                $msg = 'Thời gian bắt đầu phải sau ngày hiện tại';
                $validator->errors()->add('expired_at', $msg);
            }
            if ($end < $start){
                $msg = 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu';
                $validator->errors()->add('expired_at', $msg);
            }

        });
        return $validator;                    
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
            'use_condition.required' => 'Điều kiện sử dụng là bắt buộc',
            'description.required' => 'Chi tiết là bắt buộc',
            'code.regex' => 'Vui lòng chỉ nhập các kí tự chữ cái (A-Z), số (0-9)',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
