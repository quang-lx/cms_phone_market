<?php

namespace Modules\Admin\Http\Controllers\Api\District;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\District;
use Modules\Admin\Http\Requests\District\CreateDistrictRequest;
use Modules\Admin\Transformers\DistrictTransformer;
use Modules\Admin\Http\Requests\District\UpdateDistrictRequest;
use Modules\Admin\Repositories\DistrictRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class DistrictController extends ApiController
{
    /**
     * @var DistrictRepository
     */
    private $districtRepository;

    public function __construct(Authentication $auth, DistrictRepository $district)
    {
        parent::__construct($auth);

        $this->districtRepository = $district;
    }


    public function index(Request $request)
    {
        return DistrictTransformer::collection($this->districtRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return DistrictTransformer::collection($this->districtRepository->newQueryBuilder()->get());
    }


    public function store(CreateDistrictRequest $request)
    {
        $this->districtRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::district.message.create success'),
        ]);
    }


    public function find(District $district)
    {
        return new  DistrictTransformer($district);
    }

    public function update(District $district, UpdateDistrictRequest $request)
    {
        $this->districtRepository->update($district, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::district.message.update success'),
        ]);
    }

    public function destroy(District $district)
    {
        $this->districtRepository->destroy($district);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::district.message.delete success'),
        ]);
    }
}
