<?php

namespace Modules\Api\Http\Controllers;


use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Transformers\ProvinceTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class AppController extends ApiController {
    /** @var AreaRepository */
    public $areaRepository;
    public function __construct(Authentication $auth, AreaRepository $areaRepository)
    {
        parent::__construct($auth);

        $this->areaRepository = $areaRepository;
    }

    public function provinces() {
        $provinces = ProvinceTransformer::collection($this->areaRepository->getProvinces());
        return $this->respond($provinces, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
}
