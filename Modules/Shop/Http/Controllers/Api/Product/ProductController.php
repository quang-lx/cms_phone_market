<?php

namespace Modules\Shop\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Attribute;
use Modules\Mon\Entities\Product;
use Modules\Shop\Http\Requests\Product\CreateProductRequest;
use Modules\Shop\Repositories\PcategoryRepository;
use Modules\Shop\Repositories\PInformationRepository;
use Modules\Shop\Repositories\ProblemRepository;
use Modules\Shop\Transformers\AttributeTransformer;
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
	/**
	 * @var PcategoryRepository
	 */
	private $pcategoryRepository;

	/**
	 * @var ProblemRepository
	 */
	private $problemRepo;


	/**
	 * @var PInformationRepository
	 */
	private $pinformationRepository;

    public function __construct(Authentication $auth, ProductRepository $product, PcategoryRepository $pcategory, ProblemRepository $problemRepo, PInformationRepository $pinformationRepository)
    {
        parent::__construct($auth);

        $this->productRepository = $product;
	    $this->pcategoryRepository = $pcategory;
	    $this->problemRepo = $problemRepo;
	    $this->pinformationRepository = $pinformationRepository;
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
        $params['company_id'] = Auth::user()->company_id;
        $params['shop_id'] = Auth::user()->shop_id;

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
        $params['company_id'] = Auth::user()->company_id;

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
	public function tree(Request $request)
	{
		$categoriesTreeData =  $this->pcategoryRepository->serverPagingForTree($request);
		$problemList =  $this->problemRepo->getList($request);
		$listAttribute = $this->listAttribute();
		$listInformation = $this->pinformationRepository->listAll();

		return response()->json([

			'categories_tree' => $categoriesTreeData,
			'list_problem' => $problemList,
			'list_attribute' => $listAttribute,
			'list_information' => $listInformation
		]);
	}

	public function listAttribute() {
		//TODO
		$currentUser = Auth::user();
		$listAttribute = Attribute::query()->where('company_id', $currentUser->company_id)
			->orWhereNull('company_id')->get();
		return AttributeTransformer::collection($listAttribute);
	}

    //dashboard
    public function topProduct(Request $request)
    {
        return $this->productRepository->topProduct($request);
    }
}
