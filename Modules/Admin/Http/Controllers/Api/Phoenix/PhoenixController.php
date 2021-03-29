<?php

namespace Modules\Admin\Http\Controllers\Api\Phoenix;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Phoenix;
use Modules\Admin\Http\Requests\Phoenix\CreatePhoenixRequest;
use Modules\Admin\Transformers\PhoenixTransformer;
use Modules\Admin\Http\Requests\Phoenix\UpdatePhoenixRequest;
use Modules\Admin\Repositories\PhoenixRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class PhoenixController extends ApiController
{
    /**
     * @var PhoenixRepository
     */
    private $phoenixRepository;

    public function __construct(Authentication $auth, PhoenixRepository $phoenix)
    {
        parent::__construct($auth);

        $this->phoenixRepository = $phoenix;
    }


    public function index(Request $request)
    {
        return PhoenixTransformer::collection($this->phoenixRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return PhoenixTransformer::collection($this->phoenixRepository->newQueryBuilder()->get());
    }


    public function store(CreatePhoenixRequest $request)
    {
        $this->phoenixRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::phoenix.message.create success'),
        ]);
    }


    public function find(Phoenix $phoenix)
    {
        return new  PhoenixTransformer($phoenix);
    }

    public function update(Phoenix $phoenix, UpdatePhoenixRequest $request)
    {
        $this->phoenixRepository->update($phoenix, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::phoenix.message.update success'),
        ]);
    }

    public function destroy(Phoenix $phoenix)
    {
        $this->phoenixRepository->destroy($phoenix);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::phoenix.message.delete success'),
        ]);
    }
}
