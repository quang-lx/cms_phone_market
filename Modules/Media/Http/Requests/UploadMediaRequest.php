<?php

namespace Modules\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Modules\Media\Validators\MaxFolderSizeRule;

class UploadMediaRequest extends FormRequest
{
    public function rules()
    {
        $mimetypes = 'mimetypes:' . config('media.allowed-mimetypes');
        $maxFileSize = config('media.max-file-size');
      //  $extensions = 'mimes:' . str_replace('.', '', config('media.allowed-types'));
        $maxFileSize = $this->getMaxFileSizeInKilobytes();
		$clientMineType = $this->file('file')->getClientMimeType();
		$allowedVideoMineType = config('media.allowed-video-mimetypes');

        if (in_array($clientMineType, $allowedVideoMineType)) {
			$rules = [
				'file' => [
					'required',
					new MaxFolderSizeRule(),
					$mimetypes,
					"file",
					"size:". config('media.max-video-size')*1000,
				],
			];
		} else {
			$rules = [
				'file' => [
					'required',
					new MaxFolderSizeRule(),
					$mimetypes,
					"file",
					"size:". config('media.max-image-size')*1000,
				],
			];
		}
        return $rules;
    }

    public function messages()
    {

		$maxMsg = sprintf('File quá dung lượng. (Video %dMb, Image %dMb)', config('media.max-video-size'),config('media.max-image-size'));
        return [
            'file.size' => $maxMsg,
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
