<?php

namespace Modules\Shop\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Product;
use Modules\Shop\Http\Requests\Product\CreateProductRequest;
use Modules\Shop\Transformers\ProductTransformer;
use Modules\Shop\Http\Requests\Product\UpdateProductRequest;
use Modules\Shop\Repositories\ProductRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;
use Illuminate\Support\Facades\Auth;

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
        $params = array();
        $params['company_id'] = Auth::user()->id;
    
        $this->productRepository->create(array_merge($request->all(), $params));

        return response()->json([
            'errors' => false,
            'message' => trans('ch::product.message.create success'),
        ]);
    }


    public function find(Product $product)
    {
        return new  ProductTransformer($product);
    }

    public function update(Product $product, UpdateProductRequest $request)
    {
        $params = array();
        $params['company_id'] = Auth::user()->id;
    
        $this->productRepository->update($product, array_merge($request->all(), $params));

        return response()->json([
            'errors' => false,
            'message' => trans('ch::product.message.update success'),
        ]);
    }

    public function destroy(Product $product)
    {
        $this->productRepository->destroy($product);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::product.message.delete success'),
        ]);
    }
}
