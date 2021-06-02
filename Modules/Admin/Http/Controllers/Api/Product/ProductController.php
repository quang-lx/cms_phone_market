<?php

namespace Modules\Admin\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Product;
use Modules\Admin\Http\Requests\Product\CreateProductRequest;
use Modules\Admin\Transformers\ProductTransformer;
use Modules\Admin\Http\Requests\Product\UpdateProductRequest;
use Modules\Admin\Repositories\ProductRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class ProductController extends ApiController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(Authentication $auth, ProductRepository $product)
    {
        parent::__construct($auth);

        $this->productRepository = $product;
    }


    public function index(Request $request)
    {
        return ProductTransformer::collection($this->productRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return ProductTransformer::collection($this->productRepository->newQueryBuilder()->get());
    }


    public function store(CreateProductRequest $request)
    {
        $this->productRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::product.message.create success'),
        ]);
    }


    public function find(Product $product)
    {
        return new  ProductTransformer($product);
    }

    public function update(Product $product, UpdateProductRequest $request)
    {
        $this->productRepository->update($product, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::product.message.update success'),
        ]);
    }

    public function destroy(Product $product)
    {
        $this->productRepository->destroy($product);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::product.message.delete success'),
        ]);
    }

    //dashboard
    public function topProduct(Request $request)
    {
        return $this->productRepository->topProduct($request);
    }
}
