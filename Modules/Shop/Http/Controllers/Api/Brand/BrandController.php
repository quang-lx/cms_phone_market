<?php

namespace Modules\Shop\Http\Controllers\Api\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Brand;
use Modules\Shop\Http\Requests\Brand\CreateBrandRequest;
use Modules\Shop\Transformers\BrandTransformer;
use Modules\Shop\Http\Requests\Brand\UpdateBrandRequest;
use Modules\Shop\Repositories\BrandRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class BrandController extends ApiController
{
    /**
     * @var BrandRepository
     */
    private $brandRepository;

    public function __construct(Authentication $auth, BrandRepository $brand)
    {
        parent::__construct($auth);

        $this->brandRepository = $brand;
    }


    public function index(Request $request)
    {
        return $this->brandRepository->serverPagingFor($request);
    }


}
