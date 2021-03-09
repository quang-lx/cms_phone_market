<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Media\Validators\MaxFolderSizeRule;

class UploadMediaRequest extends FormRequest
{
    public function rules()
    {
        $mimetypes = 'mimetypes:' . config('media.allowed-mimetypes');
      //  $extensions = 'mimes:' . str_replace('.', '', config('media.allowed-types'));
        $maxFileSize = $this->getMaxFileSizeInKilobytes();

        return [
            'file' => [
                'required',
                new MaxFolderSizeRule(),
                $mimetypes,
                "max:$maxFileSize",
            ],
        ];
    }

    public function messages()
    {
        $size = $this->getMaxFileSize();

        return [
            'file.max' => trans('media::media.file too large', ['size' => $size]),
            'file.mimetypes' => 'File không đúng định dạng',
        ];
    }

    public function authorize()
    {
        return true;
    }

    private function getMaxFileSizeInKilobytes()
    {
        return $this->getMaxFileSize() * 1000;
    }

    private function getMaxFileSize()
    {
        return config('media.max-file-size');
    }
}
