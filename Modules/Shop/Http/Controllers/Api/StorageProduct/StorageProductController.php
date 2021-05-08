<?php

namespace Modules\Shop\Http\Controllers\Api\StorageProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\StorageProduct;
use Modules\Shop\Http\Requests\StorageProduct\CreateStorageProductRequest;
use Modules\Shop\Transformers\StorageProductTransformer;
use Modules\Shop\Http\Requests\StorageProduct\UpdateStorageProductRequest;
use Modules\Shop\Repositories\StorageProductRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class StorageProductController extends ApiController
{
    /**
     * @var StorageProductRepository
     */
    private $storageproductRepository;

    public function __construct(Authentication $auth, StorageProductRepository $storageproduct)
    {
        parent::__construct($auth);

        $this->storageproductRepository = $storageproduct;
    }


    public function index(Request $request)
    {
        return StorageProductTransformer::collection($this->storageproductRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return StorageProductTransformer::collection($this->storageproductRepository->newQueryBuilder()->get());
    }


    public function store(CreateStorageProductRequest $request)
    {
        $this->storageproductRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::storageproduct.message.create success'),
        ]);
    }


    public function find(StorageProduct $storageproduct)
    {
        return new  StorageProductTransformer($storageproduct);
    }

    public function update(StorageProduct $storageproduct, UpdateStorageProductRequest $request)
    {
        $this->storageproductRepository->update($storageproduct, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::storageproduct.message.update success'),
        ]);
    }

    public function destroy(StorageProduct $storageproduct)
    {
        $this->storageproductRepository->destroy($storageproduct);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::storageproduct.message.delete success'),
        ]);
    }
}
