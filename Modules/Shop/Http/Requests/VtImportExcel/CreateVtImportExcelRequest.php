<?php

namespace Modules\Shop\Http\Requests\VtImportExcel;

use Illuminate\Foundation\Http\FormRequest;

class CreateVtImportExcelRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => 'required|mimes:xlsx'
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
            'file.required' => 'Vui lòng chọn file',
            'file.mimes' => 'File sai định dạng',
        ];
    }

    public function translationMessages()
    {
        return [];
    }
}
