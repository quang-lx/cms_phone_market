<?php

namespace Modules\Admin\Http\Requests\HomeSetting;

use Illuminate\Foundation\Http\FormRequest;

class CreateHomeSettingRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'blocks.*.*' => 'required'
        ];

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
        $messages= [];
        $blocks = $this->input('blocks');
        foreach ($blocks as $key => $value) {
            $messages['blocks.' . $key .'.type.required'] = "Loại không được để trống";
            $messages['blocks.' . $key .'.title.required'] = "Tiêu đề không được để trống";
            $messages['blocks.' . $key .'.content.required'] = "Nội dung không được để trống";

        }
        return $messages;
    }

    public function translationMessages()
    {
        return [];
    }
}
