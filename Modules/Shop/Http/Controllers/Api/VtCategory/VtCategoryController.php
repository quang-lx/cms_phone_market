<?php

namespace Modules\Shop\Http\Controllers\Api\VtCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
    	$data = $request->all();
		$user = Auth::user();
		$data['company_id'] = $user->company_id;
		$data['shop_id'] = $user->shop_id;
        $this->vtcategoryRepository->create($data);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtcategory.message.create success'),
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
            'message' => trans('ch::vtcategory.message.update success'),
        ]);
    }

    public function destroy(VtCategory $vtcategory)
    {
        if ($this->vtcategoryRepository->checkExistChild($vtcategory))
        {
            return response()->json([
                'errors' => true,
                'message' => trans('ch::vtcategory.message.delete parent false'),
            ]);
        }

        if ($this->vtcategoryRepository->checkExistVtProduct($vtcategory))
        {
            return response()->json([
                'errors' => true,
                'message' => trans('ch::vtcategory.message.delete vtproduct false'),
            ]);
        }

        // $this->vtcategoryRepository->destroy($vtcategory);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtcategory.message.delete success'),
        ]);
    }
}
