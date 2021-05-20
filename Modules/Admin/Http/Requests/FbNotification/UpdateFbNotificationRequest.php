<?php

namespace Modules\Admin\Http\Requests\FbNotification;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFbNotificationRequest extends FormRequest
{
    public function rules()
    {
        $fbnotification = $this->route()->parameter('fbnotification');

        return [
            'title' => "required|unique:notifications,title,{$fbnotification->id}",
            'content' => 'required',
            'scheduled_at' => 'after:now',
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
            'title.required' => 'Tiêu đề là bắt buộc',
            'title.unique' => 'Tiêu đề đã tồn tại',
            'content.required' => 'Nội dung là bắt buộc',
            'scheduled_at.required' => 'Thời gian gửi là bắt buộc',
            'scheduled_at.after' => 'Thời gian gửi phải lớn hơn thời gian hiện tại',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
