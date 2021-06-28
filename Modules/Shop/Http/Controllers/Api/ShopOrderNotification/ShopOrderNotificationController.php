<?php

namespace Modules\Shop\Http\Controllers\Api\ShopOrderNotification;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\ShopOrderNotification;
use Modules\Shop\Http\Requests\ShopOrderNotification\CreateShopOrderNotificationRequest;
use Modules\Shop\Transformers\ShopOrderNotificationTransformer;
use Modules\Shop\Http\Requests\ShopOrderNotification\UpdateShopOrderNotificationRequest;
use Modules\Shop\Repositories\ShopOrderNotificationRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class ShopOrderNotificationController extends ApiController
{
    /**
     * @var ShopOrderNotificationRepository
     */
    private $shopordernotificationRepository;

    public function __construct(Authentication $auth, ShopOrderNotificationRepository $shopordernotification)
    {
        parent::__construct($auth);

        $this->shopordernotificationRepository = $shopordernotification;
    }


    public function index(Request $request)
    {
        return ShopOrderNotificationTransformer::collection($this->shopordernotificationRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return ShopOrderNotificationTransformer::collection($this->shopordernotificationRepository->newQueryBuilder()->get());
    }


    public function store(CreateShopOrderNotificationRequest $request)
    {
        $this->shopordernotificationRepository->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopordernotification.message.create success'),
        ]);
    }


    public function find(ShopOrderNotification $shopordernotification)
    {
        return new  ShopOrderNotificationTransformer($shopordernotification);
    }

    public function update(ShopOrderNotification $shopordernotification, UpdateShopOrderNotificationRequest $request)
    {
        $this->shopordernotificationRepository->update($shopordernotification, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopordernotification.message.update success'),
        ]);
    }

    public function destroy(ShopOrderNotification $shopordernotification)
    {
        $this->shopordernotificationRepository->destroy($shopordernotification);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::shopordernotification.message.delete success'),
        ]);
    }

    public function countUnreadNotice()
    {
        return $this->shopordernotificationRepository->countUnreadNotice();
    }
}
