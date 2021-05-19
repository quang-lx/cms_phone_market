<?php

namespace Modules\Api\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\HomeSettingRepository;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Transformers\ProductBaoHanhTransformer;
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
        $products = $this->productRepo->listByCategory($request, $request->get('include_sub', 0));
        $this->logUserSearch($request, $products);
        $data = ProductTransformer::collection($products);
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function listByService(Request $request)
    {
        $products = $this->productRepo->listByService($request);
        $data = ProductTransformer::collection($products);
        $this->logUserSearch($request, $products);
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function detail(Request $request, $id)
    {
        $product = $this->productRepo->detail($id);
        $data = $product ? new ProductDetailTransformer($product) : null;
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function baohanh(Request $request)
    {
        $data = ProductBaoHanhTransformer::collection($this->productRepo->listBaoHanh($request));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function related(Request $request, $id)
    {
        $data = ProductTransformer::collection($this->productRepo->getRelated($id, $request));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function suggested(Request $request, $id)
    {
        $data = ProductTransformer::collection($this->productRepo->getSuggested($id, $request));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function listByShop(Request $request, $shop_id)
    {
        $data = ProductTransformer::collection($this->productRepo->listByShop($request, $shop_id));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function logUserSearch(Request $request, $products) {
        if ($products->count() && $request->get('q')) {
            $user = Auth::user();
            insert_user_search(optional($user)->id, $request->header('fcm_token'), $products);
        }
    }
}
