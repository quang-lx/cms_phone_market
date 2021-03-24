<?php

namespace Modules\Admin\Http\Controllers\Api\Province;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Province;
use Modules\Admin\Http\Requests\Province\CreateProvinceRequest;
use Modules\Admin\Transformers\ProvinceTransformer;
use Modules\Admin\Http\Requests\Province\UpdateProvinceRequest;
use Modules\Admin\Repositories\ProvinceRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class ProvinceController extends ApiController
{
    /**
     * @var ProvinceRepository
     */
    private $provinceRepository;

    public function __construct(Authentication $auth, ProvinceRepository $province)
    {
        parent::__construct($auth);

        $this->provinceRepository = $province;
    }


    public function index(Request $request)
    {
        return ProvinceTransformer::collection($this->provinceRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return ProvinceTransformer::collection($this->provinceRepository->newQueryBuilder()->get());
    }


    public function store(CreateProvinceRequest $request)
    {
        $this->provinceRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::province.message.create success'),
        ]);
    }


    public function find(Province $province)
    {
        return new  ProvinceTransformer($province);
    }

    public function update(Province $province, UpdateProvinceRequest $request)
    {
        $this->provinceRepository->update($province, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::province.message.update success'),
        ]);
    }

    public function destroy(Province $province)
    {
        $this->provinceRepository->destroy($province);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::province.message.delete success'),
        ]);
    }
}
