<?php

namespace Modules\Api\Http\Controllers;


use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\GiftRepository;
use Modules\Api\Repositories\PaymentMethodRepository;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Api\Repositories\VoucherRepository;
use Modules\Api\Transformers\DistrictTransformer;
use Modules\Api\Transformers\PhoenixesTransformer;
use Modules\Api\Transformers\ProvinceTransformer;
use Modules\Api\Transformers\ShipTypeTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Http\Controllers\ApiController;

class AppController extends ApiController
{

    /** @var AreaRepository */
    public $areaRepository;

    /** @var ShipTypeRepository */
    public $shipTypeRepo;

    /** @var PaymentMethodRepository */
    public $paymentMethodRepo;

    /** @var VoucherRepository */
    public $voucherRepo;

	/** @var GiftRepository */
	public $giftRepo;

    public function __construct(Authentication $auth, AreaRepository $areaRepository, ShipTypeRepository $shipTypeRepo, PaymentMethodRepository $paymentMethodRepo,
                                VoucherRepository $voucherRepo, GiftRepository $giftRepo)
    {
        parent::__construct($auth);

        $this->areaRepository = $areaRepository;
        $this->shipTypeRepo = $shipTypeRepo;
        $this->paymentMethodRepo = $paymentMethodRepo;
        $this->voucherRepo = $voucherRepo;
        $this->giftRepo = $giftRepo;
    }

    public function provinces()
    {
        $provinces = ProvinceTransformer::collection($this->areaRepository->getProvinces());
        return $this->respond($provinces, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function districts(Request $request)
    {
        $districts = DistrictTransformer::collection($this->areaRepository->getDistricts($request));
        return $this->respond($districts, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function phoenixes(Request $request)
    {
        $phoenixes = PhoenixesTransformer::collection($this->areaRepository->getPhoenixes($request));
        return $this->respond($phoenixes, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function allArea(Request $request)
    {
        $area = $this->areaRepository->getAll($request);

        $data = [
            'provinces' => ProvinceTransformer::collection($area['provinces']),
            'districts' => DistrictTransformer::collection($area['districts']),
            'phoenixes' => PhoenixesTransformer::collection($area['phoenixes']),
        ];
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function listShipType(Request $request)
    {
        $data = $this->shipTypeRepo->getAll($request);
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function listPaymentMethod(Request $request)
    {
        $data = $this->paymentMethodRepo->getAll($request);
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function setting(Request $request)
    {
        $shipType = $this->shipTypeRepo->getAll($request);
        $paymentMethod = $this->paymentMethodRepo->getAll($request);
        $orderSetting = $this->getOrderSetting();
        $data = [
            'ship_type' => $shipType,
            'payment_method' => $paymentMethod,
            'order_setting' => $orderSetting,
        ];
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

    }

    public function vouchers(Request $request)
    {
        $data = $this->voucherRepo->getListVoucher($request);
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

    }
	public function gifts(Request $request)
	{
		$data = $this->giftRepo->serverPagingFor($request);
		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

	}
    protected function getOrderSetting()
    {
        $orderSetting = Cache::rememberForever(CacheKey::ORDER_SETTING, function () {
            return [
                'order_type' => [
                    [
                        'code' => Orders::TYPE_SUA_CHUA,
                        'label' => 'Sửa chữa'
                    ],
                    [
                        'code' => Orders::TYPE_BAO_HANH,
                        'label' => 'Bảo hành'
                    ],
                    [
                        'code' => Orders::TYPE_MUA_HANG,
                        'label' => 'Mua hàng'
                    ]
                ],
                'order_status' => [
                    Orders::TYPE_SUA_CHUA => [
                        [
                            'code' => 'created',
                            'label' => trans('order.order_status.created')
                        ],
                        [
                            'code' => 'confirmed',
                            'label' => trans('order.order_status.confirmed')
                        ],
                        [
                            'code' => 'fixing',
                            'label' => trans('order.order_status.fixing')
                        ],
                        [
                            'code' => 'sending',
                            'label' => trans('order.order_status.sending')
                        ],
                        [
                            'code' => 'done',
                            'label' => trans('order.order_status.done')
                        ],
                        [
                            'code' => 'cancel',
                            'label' => trans('order.order_status.cancel')
                        ],
                    ],
                    Orders::TYPE_BAO_HANH => [
                        [
                            'code' => 'created',
                            'label' => trans('order.order_status.created')
                        ],

                        [
                            'code' => 'warranting',
                            'label' => trans('order.order_status.warranting')
                        ],
                        [
                            'code' => 'sending',
                            'label' => trans('order.order_status.sending')
                        ],
                        [
                            'code' => 'done',
                            'label' => trans('order.order_status.done')
                        ],
                        [
                            'code' => 'cancel',
                            'label' => trans('order.order_status.cancel')
                        ],
                    ],
                    Orders::TYPE_MUA_HANG => [
                        [
                            'code' => 'created',
                            'label' => trans('order.order_status.created')
                        ],

                        [
                            'code' => 'sending',
                            'label' => trans('order.order_status.sending')
                        ],
                        [
                            'code' => 'done',
                            'label' => trans('order.order_status.done')
                        ],
                        [
                            'code' => 'cancel',
                            'label' => trans('order.order_status.cancel')
                        ],
                    ]

                ]
            ];
        });
    }
}
