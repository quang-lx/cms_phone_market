<?php

namespace Modules\Shop\Http\Controllers\Api\ShopShipType;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\ShopShipType;
use Modules\Shop\Http\Requests\ShopShipType\CreateShopShipTypeRequest;
use Modules\Shop\Transformers\ShopShipTypeTransformer;
use Modules\Shop\Http\Requests\ShopShipType\UpdateShopShipTypeRequest;
use Modules\Shop\Repositories\ShopShipTypeRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class ShopShipTypeController extends ApiController
{
    /**
     * @var ShopShipTypeRepository
     */
    private $shopshiptypeRepository;

    public function __construct(Authentication $auth, ShopShipTypeRepository $shopshiptype)
    {
        parent::__construct($auth);

        $this->shopshiptypeRepository = $shopshiptype;
    }


    public function index(Request $request)
    {
        return ShopShipTypeTransformer::collection($this->shopshiptypeRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return ShopShipTypeTransformer::collection($this->shopshiptypeRepository->newQueryBuilder()->get());
    }


    public function store(CreateShopShipTypeRequest $request)
    {
        $this->shopshiptypeRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopshiptype.message.create success'),
        ]);
    }

    public function create_or_update(CreateShopShipTypeRequest $request)
    {
        $this->shopshiptypeRepository->create_or_update($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopshiptype.message.create success'),
        ]);
    }


    public function find(ShopShipType $shopshiptype)
    {
        return new  ShopShipTypeTransformer($shopshiptype);
    }

    public function update(ShopShipType $shopshiptype, UpdateShopShipTypeRequest $request)
    {
        $this->shopshiptypeRepository->update($shopshiptype, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopshiptype.message.update success'),
        ]);
    }

    public function destroy(ShopShipType $shopshiptype)
    {
        $this->shopshiptypeRepository->destroy($shopshiptype);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopshiptype.message.delete success'),
        ]);
    }
}
