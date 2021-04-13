<?php

namespace Modules\Admin\Http\Controllers\Api\Banners;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Banners;
use Modules\Admin\Http\Requests\Banners\CreateBannersRequest;
use Modules\Admin\Transformers\BannersTransformer;
use Modules\Admin\Http\Requests\Banners\UpdateBannersRequest;
use Modules\Admin\Repositories\BannersRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class BannersController extends ApiController
{
    /**
     * @var BannersRepository
     */
    private $bannersRepository;

    public function __construct(Authentication $auth, BannersRepository $banners)
    {
        parent::__construct($auth);

        $this->bannersRepository = $banners;
    }


    public function index(Request $request)
    {
        return BannersTransformer::collection($this->bannersRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return BannersTransformer::collection($this->bannersRepository->newQueryBuilder()->get());
    }


    public function store(CreateBannersRequest $request)
    {
        $this->bannersRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::banners.message.create success'),
        ]);
    }


    public function find(Banners $banners)
    {
        return new  BannersTransformer($banners);
    }

    public function update(Banners $banners, UpdateBannersRequest $request)
    {
        $this->bannersRepository->update($banners, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::banners.message.update success'),
        ]);
    }

    public function destroy(Banners $banners)
    {
        $this->bannersRepository->destroy($banners);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::banners.message.delete success'),
        ]);
    }
}
