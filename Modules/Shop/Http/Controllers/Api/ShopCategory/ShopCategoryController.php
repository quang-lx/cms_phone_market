<?php

namespace Modules\Shop\Http\Controllers\Api\ShopCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\ShopCategory;
use Modules\Shop\Http\Requests\ShopCategory\CreateShopCategoryRequest;
use Modules\Shop\Transformers\ShopCategoryTransformer;
use Modules\Shop\Http\Requests\ShopCategory\UpdateShopCategoryRequest;
use Modules\Shop\Repositories\ShopCategoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class ShopCategoryController extends ApiController
{
    /**
     * @var ShopCategoryRepository
     */
    private $shopcategoryRepository;

    public function __construct(Authentication $auth, ShopCategoryRepository $shopcategory)
    {
        parent::__construct($auth);

        $this->shopcategoryRepository = $shopcategory;
    }


    public function index(Request $request)
    {
        return ShopCategoryTransformer::collection($this->shopcategoryRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return ShopCategoryTransformer::collection($this->shopcategoryRepository->newQueryBuilder()->get());
    }


    public function store(CreateShopCategoryRequest $request)
    {
        $this->shopcategoryRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopcategory.message.create success'),
        ]);
    }


    public function find(ShopCategory $shopcategory)
    {
        return new  ShopCategoryTransformer($shopcategory);
    }

    public function update(ShopCategory $shopcategory, UpdateShopCategoryRequest $request)
    {
        $this->shopcategoryRepository->update($shopcategory, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopcategory.message.update success'),
        ]);
    }

    public function destroy(ShopCategory $shopcategory)
    {
        $this->shopcategoryRepository->destroy($shopcategory);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopcategory.message.delete success'),
        ]);
    }
}
