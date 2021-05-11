<?php

namespace Modules\Admin\Http\Requests\ShipType;

use Illuminate\Foundation\Http\FormRequest;

class CreateShipTypeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|unique:ship_type,name',
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
            'name.required' => 'Tên là bắt buộc',
            'name.unique' => 'Tên đã tồn tại',

        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
