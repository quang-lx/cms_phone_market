<?php

namespace Modules\Api\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\HomeSettingRepository;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Transformers\DistrictTransformer;
use Modules\Api\Transformers\PhoenixesTransformer;
use Modules\Api\Transformers\ProductTransformer;
use Modules\Api\Transformers\ProvinceTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class HomeController extends ApiController
{

    /** @var HomeSettingRepository */
    public $homesettingRepository;


    /** @var ProductRepository */
    public $productRepo;


    public function __construct(Authentication $auth, HomeSettingRepository $homesettingRepository, ProductRepository $productRepo)
    {
        parent::__construct($auth);

        $this->homesettingRepository = $homesettingRepository;
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        return $this->respond($this->homesettingRepository->get(), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }


    public function buyNow(Request $request)
    {
        $data = ProductTransformer::collection($this->productRepo->buyNow($request));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function bestSell(Request $request)
    {
        $data = ProductTransformer::collection($this->productRepo->bestSell($request));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
}
