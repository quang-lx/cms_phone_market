<?php

namespace Modules\Shop\Http\Controllers\Api\VtCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\VtCategory;
use Modules\Shop\Http\Requests\VtCategory\CreateVtCategoryRequest;
use Modules\Shop\Transformers\VtCategoryTransformer;
use Modules\Shop\Http\Requests\VtCategory\UpdateVtCategoryRequest;
use Modules\Shop\Repositories\VtCategoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class VtCategoryController extends ApiController
{
    /**
     * @var VtCategoryRepository
     */
    private $vtcategoryRepository;

    public function __construct(Authentication $auth, VtCategoryRepository $vtcategory)
    {
        parent::__construct($auth);

        $this->vtcategoryRepository = $vtcategory;
    }


    public function index(Request $request)
    {
        return VtCategoryTransformer::collection($this->vtcategoryRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return VtCategoryTransformer::collection($this->vtcategoryRepository->newQueryBuilder()->get());
    }


    public function store(CreateVtCategoryRequest $request)
    {
        $this->vtcategoryRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::vtcategory.message.create success'),
        ]);
    }


    public function find(VtCategory $vtcategory)
    {
        return new  VtCategoryTransformer($vtcategory);
    }

    public function update(VtCategory $vtcategory, UpdateVtCategoryRequest $request)
    {
        $this->vtcategoryRepository->update($vtcategory, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::vtcategory.message.update success'),
        ]);
    }

    public function destroy(VtCategory $vtcategory)
    {
        $this->vtcategoryRepository->destroy($vtcategory);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::vtcategory.message.delete success'),
        ]);
    }
}
