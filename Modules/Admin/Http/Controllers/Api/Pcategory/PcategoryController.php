<?php

namespace Modules\Admin\Http\Controllers\Api\Pcategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Pcategory;
use Modules\Admin\Http\Requests\Pcategory\CreatePcategoryRequest;
use Modules\Admin\Transformers\PcategoryTransformer;
use Modules\Admin\Http\Requests\Pcategory\UpdatePcategoryRequest;
use Modules\Admin\Repositories\PcategoryRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class PcategoryController extends ApiController
{
    /**
     * @var PcategoryRepository
     */
    private $pcategoryRepository;

    public function __construct(Authentication $auth, PcategoryRepository $pcategory)
    {
        parent::__construct($auth);

        $this->pcategoryRepository = $pcategory;
    }


    public function index(Request $request)
    {
        return PcategoryTransformer::collection($this->pcategoryRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return PcategoryTransformer::collection($this->pcategoryRepository->newQueryBuilder()->get());
    }


    public function store(CreatePcategoryRequest $request)
    {
        $this->pcategoryRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::pcategory.message.create success'),
        ]);
    }


    public function find(Pcategory $pcategory)
    {
        return new  PcategoryTransformer($pcategory);
    }

    public function update(Pcategory $pcategory, UpdatePcategoryRequest $request)
    {
        $this->pcategoryRepository->update($pcategory, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::pcategory.message.update success'),
        ]);
    }

    public function destroy(Pcategory $pcategory)
    {
        $this->pcategoryRepository->destroy($pcategory);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::pcategory.message.delete success'),
        ]);
    }
}
