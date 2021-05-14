<?php

namespace Modules\Api\Http\Controllers;

use App\Events\NeedCreateUserSmsToken;
use App\Rules\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\CategoryRepository;
use Modules\Api\Repositories\OrderRepository;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends ApiController
{

    /** @var OrderRepository */
    public $orderRepo;

    public function __construct(Authentication $auth, OrderRepository $orderRepo, ShipTypeRepository $shipTypeRepo, AreaRepository $areaRepo, AddressRepository $addressRepo)
    {
        parent::__construct($auth);

        $this->orderRepo = $orderRepo;
        $this->shipTypeRepo = $shipTypeRepo;
        $this->areaRepo = $areaRepo;
        $this->addressRepo = $addressRepo;
    }

    public function store(Request $request)
    {


        $validator = $this->validateStore($request);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->respond($errors, $errors->first(), ErrorCode::ERR422);
        }

        $result = $this->orderRepo->placeMultipleOrder($request, Auth::user());
        if ($result === true) {
            return $this->respond(null, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
        }
        list ($errorMsg, $errorCode) = $result;
        return $this->respond(null, $errorMsg, $errorCode);

    }


    public function validateStore(Request $request)
    {
        $orderType = $request->get('type');
        $rules = [];
        $messages = [];
        $rules = [
            'orders.*.order_type' => 'required',
            'orders.*.ship_type_id' => 'required',
            'orders.*.ship_address_id' => 'required',
            'orders.*.quantity' => 'required',
            'orders.*.product_id' => 'required',
        ];

        $orders = $request->get('orders');
        foreach ($orders as $key => $value) {
            $messages['orders.' . $key . '.order_type.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Loại đơn hàng']);
            $messages['orders.' . $key . '.ship_type_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Hình thức vận chuyển']);
            $messages['orders.' . $key . '.ship_address_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Địa chỉ nhận hàng']);
            $messages['orders.' . $key . '.quantity.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Số lượng']);
            $messages['orders.' . $key . '.product_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Sản phẩm']);

        }

        if ($orderType == Orders::TYPE_SUA_CHUA) {

        }
        return $this->validate($request, $rules, $messages);

    }
}
