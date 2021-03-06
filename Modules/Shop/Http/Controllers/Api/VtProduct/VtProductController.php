<?php

namespace Modules\Shop\Http\Controllers\Api\VtProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Mon\Entities\VtProduct;
use Modules\Shop\Http\Requests\VtProduct\CreateVtProductRequest;
use Modules\Shop\Transformers\VtProductTransformer;
use Modules\Shop\Http\Requests\VtProduct\UpdateVtProductRequest;
use Modules\Shop\Repositories\VtProductRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class VtProductController extends ApiController
{
    /**
     * @var VtProductRepository
     */
    private $vtproductRepository;

    public function __construct(Authentication $auth, VtProductRepository $vtproduct)
    {
        parent::__construct($auth);

        $this->vtproductRepository = $vtproduct;
    }


    public function index(Request $request)
    {
        return VtProductTransformer::collection($this->vtproductRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return VtProductTransformer::collection($this->vtproductRepository->newQueryBuilder()->get());
    }


    public function store(CreateVtProductRequest $request)
    {
    	$data = $request->all();
		$user = Auth::user();

		$data['company_id'] = $user->company_id;
		$data['shop_id'] = $user->shop_id;
        $this->vtproductRepository->create($data);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtproduct.message.create success'),
        ]);
    }


    public function find(VtProduct $vtproduct)
    {
        return new  VtProductTransformer($vtproduct);
    }

    public function update(VtProduct $vtproduct, UpdateVtProductRequest $request)
    {
        $this->vtproductRepository->update($vtproduct, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtproduct.message.update success'),
        ]);
    }

    public function destroy(VtProduct $vtproduct)
    {
        if ($this->vtproductRepository->checkAmountVtProduct($vtproduct))
        {
            return response()->json([
                'errors' => true,
                'message' => trans('ch::vtproduct.message.delete vtproduct false'),
            ]);
        }
        $this->vtproductRepository->destroy($vtproduct);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtproduct.message.delete success'),
        ]);
    }


    public function import(Request $request)
    {
        $this->vtproductRepository->import($request->vtimportexcel);

        return response()->json([
            'errors' => false,
            'message' => trans('ch::vtproduct.message.import success'),
        ]);
    }
}
