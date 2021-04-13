<?php

namespace Modules\Admin\Http\Controllers\Api\HomeSetting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\HomeSetting;
use Modules\Admin\Http\Requests\HomeSetting\CreateHomeSettingRequest;
use Modules\Admin\Transformers\HomeSettingTransformer;
use Modules\Admin\Http\Requests\HomeSetting\UpdateHomeSettingRequest;
use Modules\Admin\Repositories\HomeSettingRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class HomeSettingController extends ApiController
{
    /**
     * @var HomeSettingRepository
     */
    private $homesettingRepository;

    public function __construct(Authentication $auth, HomeSettingRepository $homesetting)
    {
        parent::__construct($auth);

        $this->homesettingRepository = $homesetting;
    }


    public function index(Request $request)
    {
        return HomeSettingTransformer::collection($this->homesettingRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return HomeSettingTransformer::collection($this->homesettingRepository->newQueryBuilder()->get());
    }


    public function store(CreateHomeSettingRequest $request)
    {
        $this->homesettingRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::homesetting.message.create success'),
        ]);
    }


    public function find(HomeSetting $homesetting)
    {
        return new  HomeSettingTransformer($homesetting);
    }

    public function update(HomeSetting $homesetting, UpdateHomeSettingRequest $request)
    {
        $this->homesettingRepository->update($homesetting, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::homesetting.message.update success'),
        ]);
    }

    public function destroy(HomeSetting $homesetting)
    {
        $this->homesettingRepository->destroy($homesetting);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::homesetting.message.delete success'),
        ]);
    }
}
