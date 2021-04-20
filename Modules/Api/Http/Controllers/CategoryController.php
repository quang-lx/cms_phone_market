<?php

namespace Modules\Api\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\CategoryRepository;
use Modules\Api\Repositories\HomeSettingRepository;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Transformers\PCategoryTransformer;
use Modules\Api\Transformers\ProductTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class CategoryController extends ApiController
{

    /** @var CategoryRepository */
    public $categoryRepo;

    public function __construct(Authentication $auth, CategoryRepository $categoryRepo)
    {
        parent::__construct($auth);

        $this->categoryRepo = $categoryRepo;
    }

    public function listSubCat(Request $request, $category_id)
    {
    	$data = PCategoryTransformer::collection($this->categoryRepo->listSubCat($request, $category_id));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function listByServiceType (Request $request) {
	    $data = PCategoryTransformer::collection($this->categoryRepo->listByServiceType($request));
	    return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

}
