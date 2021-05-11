<?php

namespace Modules\Admin\Http\Controllers\Api\ShipType;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\ShipType;
use Modules\Admin\Http\Requests\ShipType\CreateShipTypeRequest;
use Modules\Admin\Transformers\ShipTypeTransformer;
use Modules\Admin\Http\Requests\ShipType\UpdateShipTypeRequest;
use Modules\Admin\Repositories\ShipTypeRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class ShipTypeController extends ApiController
{
    /**
     * @var ShipTypeRepository
     */
    private $shiptypeRepository;

    public function __construct(Authentication $auth, ShipTypeRepository $shiptype)
    {
        parent::__construct($auth);

        $this->shiptypeRepository = $shiptype;
    }


    public function index(Request $request)
    {
        return ShipTypeTransformer::collection($this->shiptypeRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return ShipTypeTransformer::collection($this->shiptypeRepository->newQueryBuilder()->get());
    }


    public function store(CreateShipTypeRequest $request)
    {
        $this->shiptypeRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shiptype.message.create success'),
        ]);
    }


    public function find(ShipType $shiptype)
    {
        return new  ShipTypeTransformer($shiptype);
    }

    public function update(ShipType $shiptype, UpdateShipTypeRequest $request)
    {
        $this->shiptypeRepository->update($shiptype, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shiptype.message.update success'),
        ]);
    }

    public function destroy(ShipType $shiptype)
    {
        $this->shiptypeRepository->destroy($shiptype);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shiptype.message.delete success'),
        ]);
    }
}
