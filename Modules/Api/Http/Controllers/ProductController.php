<?php

namespace Modules\Api\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\HomeSettingRepository;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Transformers\ProductTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class ProductController extends ApiController
{

    /** @var ProductRepository */
    public $productRepo;

    public function __construct(Authentication $auth, ProductRepository $productRepo)
    {
        parent::__construct($auth);

        $this->productRepo = $productRepo;
    }

    public function listByCategory(Request $request)
    {
    	$data = ProductTransformer::collection($this->productRepo->listBuyCategory($request));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

}
