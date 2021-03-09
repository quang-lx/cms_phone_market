<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Entities\ErrorCode;
use Modules\Media\Http\Requests\UploadMediaRequest;
use Modules\Media\Image\Imagy;
use Modules\Media\Repositories\MediaRepository;
use Modules\Media\Services\FileService;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class MediaController extends ApiController
{
    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var MediaRepository
     */
    private $file;

    /**
     * @var Imagy
     */
    private $imagy;

    public function __construct(Authentication $auth, FileService $fileService, MediaRepository $file, Imagy $imagy)
    {
        parent::__construct($auth);
        $this->fileService = $fileService;
        $this->file = $file;
        $this->imagy = $imagy;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param UploadMediaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR16_MSG, ErrorCode::ERR16);
        }

//        $extensions = 'mimes:' . str_replace('.', '', config('media.allowed-types'));
        $mimetypes = 'mimetypes:' . config('media.allowed-mimetypes');
        $validator = Validator::make($request->all(), [
            'file' => $mimetypes,
        ]);
        if($validator->fails()){
            return $this->respond([], ErrorCode::ERR17_MSG, ErrorCode::ERR17);
        }

        $savedFile = $this->fileService->store($request->file('file'), $request->get('parent_id')? : 0);

        if (is_string($savedFile)) {

            return $this->respond([], $savedFile, ErrorCode::ERR17);

        }

        return $this->respond(new \Modules\Api\Transformers\MediaTransformer($savedFile), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);


    }


}
