<?php

namespace Modules\Api\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\HomeSettingRepository;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Transformers\ProductDetailTransformer;
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
    	$data = ProductTransformer::collection($this->productRepo->listByCategory($request, $request->get('include_sub', 0)));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
	public function listByService(Request $request)
	{
		$data = ProductTransformer::collection($this->productRepo->listByService($request));
		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
	public function detail(Request $request, $id)
	{
		$product = $this->productRepo->detail($id);
		$data = $product? new ProductDetailTransformer($product): null;
		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
	public function baohanh(Request $request) {
		$data = ProductTransformer::collection($this->productRepo->listBaoHanh($request));
		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
}
