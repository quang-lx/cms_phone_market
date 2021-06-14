<?php

namespace Modules\Shop\Http\Controllers\Api\VtShopProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\VtShopProduct;
use Modules\Shop\Http\Requests\VtShopProduct\CreateVtShopProductRequest;
use Modules\Shop\Transformers\VtShopProductTransformer;
use Modules\Shop\Http\Requests\VtShopProduct\UpdateVtShopProductRequest;
use Modules\Shop\Repositories\VtShopProductRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class VtShopProductController extends ApiController
{
    /**
     * @var VtShopProductRepository
     */
    private $vtshopproductRepository;

    public function __construct(Authentication $auth, VtShopProductRepository $vtshopproduct)
    {
        parent::__construct($auth);

        $this->vtshopproductRepository = $vtshopproduct;
    }


    public function index(Request $request)
    {
        return VtShopProductTransformer::collection($this->vtshopproductRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return VtShopProductTransformer::collection($this->vtshopproductRepository->newQueryBuilder()->get());
    }


    public function store(CreateVtShopProductRequest $request)
    {
        $this->vtshopproductRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtshopproduct.message.create success'),
        ]);
    }


    public function find(VtShopProduct $vtshopproduct)
    {
        return new  VtShopProductTransformer($vtshopproduct);
    }

    public function update(VtShopProduct $vtshopproduct, UpdateVtShopProductRequest $request)
    {
        $this->vtshopproductRepository->update($vtshopproduct, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtshopproduct.message.update success'),
        ]);
    }

    public function destroy(VtShopProduct $vtshopproduct)
    {
        $this->vtshopproductRepository->destroy($vtshopproduct);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtshopproduct.message.delete success'),
        ]);
    }
}
