<?php

namespace Modules\Admin\Http\Controllers\Api\PInformation;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\PInformation;
use Modules\Admin\Http\Requests\PInformation\CreatePInformationRequest;
use Modules\Admin\Transformers\PInformationTransformer;
use Modules\Admin\Http\Requests\PInformation\UpdatePInformationRequest;
use Modules\Admin\Repositories\PInformationRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class PInformationController extends ApiController
{
    /**
     * @var PInformationRepository
     */
    private $pinformationRepository;

    public function __construct(Authentication $auth, PInformationRepository $pinformation)
    {
        parent::__construct($auth);

        $this->pinformationRepository = $pinformation;
    }


    public function index(Request $request)
    {
        return PInformationTransformer::collection($this->pinformationRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return PInformationTransformer::collection($this->pinformationRepository->newQueryBuilder()->get());
    }


    public function store(CreatePInformationRequest $request)
    {
        $this->pinformationRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::pinformation.message.create success'),
        ]);
    }


    public function find(PInformation $pinformation)
    {
        return new  PInformationTransformer($pinformation);
    }

    public function update(PInformation $pinformation, UpdatePInformationRequest $request)
    {
        $this->pinformationRepository->update($pinformation, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::pinformation.message.update success'),
        ]);
    }

    public function destroy(PInformation $pinformation)
    {
        $this->pinformationRepository->destroy($pinformation);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::pinformation.message.delete success'),
        ]);
    }
}
