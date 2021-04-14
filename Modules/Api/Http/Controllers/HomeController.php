<?php

namespace Modules\Api\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\HomeSettingRepository;
use Modules\Api\Transformers\DistrictTransformer;
use Modules\Api\Transformers\PhoenixesTransformer;
use Modules\Api\Transformers\ProvinceTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class HomeController extends ApiController
{

    /** @var HomeSettingRepository */
    public $homesettingRepository;

    public function __construct(Authentication $auth, HomeSettingRepository $homesettingRepository)
    {
        parent::__construct($auth);

        $this->homesettingRepository = $homesettingRepository;
    }

    public function index()
    {
        return $this->respond($this->homesettingRepository->get(), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

}
