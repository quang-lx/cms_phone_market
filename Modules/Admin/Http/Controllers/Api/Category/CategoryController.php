<?php

namespace Modules\Admin\Http\Controllers\Api\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Transformers\CategoryTransformer;
use Modules\Mon\Entities\Category;
use Modules\Admin\Http\Requests\Category\CreateCategoryRequest;
use Modules\Admin\Http\Requests\Category\UpdateCategoryRequest;
use Modules\Admin\Repositories\CategoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class CategoryController extends ApiController
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(Authentication $auth, CategoryRepository $category)
    {
        parent::__construct($auth);

        $this->categoryRepository = $category;
    }


    public function index(Request $request)
    {
        return CategoryTransformer::collection($this->categoryRepository->serverPagingFor($request));
    }

    public function tree(Request $request)
    {
        return $this->categoryRepository->serverPagingForTree($request);
    }


    public function all(Request $request)
    {
        return CategoryTransformer::collection($this->categoryRepository->newQueryBuilder()->get());
    }


    public function store(CreateCategoryRequest $request)
    {
        $this->categoryRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::category.message.create success'),
        ]);
    }


    public function find(Category $category)
    {
        return new  CategoryTransformer($category);
    }

    public function update(Category $category, UpdateCategoryRequest $request)
    {
        $this->categoryRepository->update($category, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::category.message.update success'),
        ]);
    }

    public function destroy(Category $category)
    {
        $this->categoryRepository->destroy($category);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::category.message.delete success'),
        ]);
    }
}
