<?php

namespace Modules\Admin\Http\Controllers\Api\FbNotification;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\FbNotification;
use Modules\Admin\Http\Requests\FbNotification\CreateFbNotificationRequest;
use Modules\Admin\Transformers\FbNotificationTransformer;
use Modules\Admin\Http\Requests\FbNotification\UpdateFbNotificationRequest;
use Modules\Admin\Repositories\FbNotificationRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class FbNotificationController extends ApiController
{
    /**
     * @var FbNotificationRepository
     */
    private $fbnotificationRepository;

    public function __construct(Authentication $auth, FbNotificationRepository $fbnotification)
    {
        parent::__construct($auth);

        $this->fbnotificationRepository = $fbnotification;
    }


    public function index(Request $request)
    {
        return FbNotificationTransformer::collection($this->fbnotificationRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return FbNotificationTransformer::collection($this->fbnotificationRepository->newQueryBuilder()->get());
    }


    public function store(CreateFbNotificationRequest $request)
    {
        $this->fbnotificationRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::fbnotification.message.create success'),
        ]);
    }


    public function find(FbNotification $fbnotification)
    {
        return new  FbNotificationTransformer($fbnotification);
    }

    public function update(FbNotification $fbnotification, UpdateFbNotificationRequest $request)
    {
        $this->fbnotificationRepository->update($fbnotification, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::fbnotification.message.update success'),
        ]);
    }

    public function destroy(FbNotification $fbnotification)
    {
        $this->fbnotificationRepository->destroy($fbnotification);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::fbnotification.message.delete success'),
        ]);
    }
}
