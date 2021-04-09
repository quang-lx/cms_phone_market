<?php

namespace Modules\Admin\Http\Controllers\Api\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Brand;
use Modules\Admin\Http\Requests\Brand\CreateBrandRequest;
use Modules\Admin\Transformers\BrandTransformer;
use Modules\Admin\Http\Requests\Brand\UpdateBrandRequest;
use Modules\Admin\Repositories\BrandRepository;
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
        return BrandTransformer::collection($this->brandRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return BrandTransformer::collection($this->brandRepository->newQueryBuilder()->get());
    }


    public function store(CreateBrandRequest $request)
    {
        $this->brandRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::brand.message.create success'),
        ]);
    }


    public function find(Brand $brand)
    {
        return new  BrandTransformer($brand);
    }

    public function update(Brand $brand, UpdateBrandRequest $request)
    {
        $this->brandRepository->update($brand, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::brand.message.update success'),
        ]);
    }

    public function destroy(Brand $brand)
    {
        $this->brandRepository->destroy($brand);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::brand.message.delete success'),
        ]);
    }
}
