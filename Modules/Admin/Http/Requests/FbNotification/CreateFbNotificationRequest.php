<?php

namespace Modules\Admin\Http\Requests\FbNotification;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Mon\Entities\FbNotification;

class CreateFbNotificationRequest extends FormRequest
{
    public function rules()
    {
        $rules =  [
            'title' => 'required',
            'content' => 'required',
        ];
        if ($this->request->get('link_type')) {
            $rules['link_content'] = 'required';
        }
        return $rules;
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
        $linkContent = $this->request->get('link_type') == 'Link'? 'Link Url': 'ID tin tức';
        return [
            'title.required' => 'Tên notification là bắt buộc',
            'content.required' => 'Nội dung là bắt buộc',
            'link_content.required' => $linkContent.' là bắt buộc',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
