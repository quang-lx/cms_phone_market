<?php

namespace Modules\Api\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Transformers\DistrictTransformer;
use Modules\Api\Transformers\PhoenixesTransformer;
use Modules\Api\Transformers\ProvinceTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class AppController extends ApiController
{

    /** @var AreaRepository */
    public $areaRepository;

    public function __construct(Authentication $auth, AreaRepository $areaRepository)
    {
        parent::__construct($auth);

        $this->areaRepository = $areaRepository;
    }

    public function provinces()
    {
        $provinces = ProvinceTransformer::collection($this->areaRepository->getProvinces());
        return $this->respond($provinces, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function districts(Request $request)
    {
        $districts = DistrictTransformer::collection($this->areaRepository->getDistricts($request));
        return $this->respond($districts, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function phoenixes(Request $request)
    {
        $phoenixes = PhoenixesTransformer::collection($this->areaRepository->getPhoenixes($request));
        return $this->respond($phoenixes, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function allArea(Request $request)
    {
        $area = $this->areaRepository->getAll($request);

        $data = [
          'provinces' =>   ProvinceTransformer::collection($area['provinces']),
          'districts' =>   DistrictTransformer::collection($area['districts']),
          'phoenixes' =>   PhoenixesTransformer::collection($area['phoenixes']),
        ];
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
}
