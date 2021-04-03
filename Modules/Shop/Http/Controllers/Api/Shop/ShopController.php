<?php

namespace Modules\Shop\Http\Controllers\Api\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Shop;
use Modules\Shop\Http\Requests\Shop\CreateShopRequest;
use Modules\Shop\Transformers\ShopTransformer;
use Modules\Shop\Http\Requests\Shop\UpdateShopRequest;
use Modules\Shop\Repositories\ShopRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;
use Illuminate\Support\Facades\Auth;

class ShopController extends ApiController
{
    /**
     * @var ShopRepository
     */
    private $shopRepository;

    public function __construct(Authentication $auth, ShopRepository $shop)
    {
        parent::__construct($auth);

        $this->shopRepository = $shop;
    }


    public function index(Request $request)
    {
        return ShopTransformer::collection($this->shopRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return ShopTransformer::collection($this->shopRepository->newQueryBuilder()->get());
    }


    public function store(CreateShopRequest $request)
    {
        $arrReq = $request->all();
        $arrReq['company_id'] = Auth::user()->company_id;
        $arrReq['status'] = $arrReq['status'] == 'publish' ? 1 : 0;

//        $this->shopRepository->create($request->all());
        $this->shopRepository->create($arrReq);

        return response()->json([
            'errors' => false,
            'message' => trans('shop::shop.message.create success'),
        ]);
    }


    public function find(Shop $shop)
    {
        return new  ShopTransformer($shop);
    }

    public function update(Shop $shop, UpdateShopRequest $request)
    {
        $this->shopRepository->update($shop, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('shop::shop.message.update success'),
        ]);
    }

    public function destroy(Shop $shop)
    {
        $this->shopRepository->destroy($shop);

        return response()->json([
            'errors' => false,
            'message' => trans('shop::shop.message.delete success'),
        ]);
    }
}
