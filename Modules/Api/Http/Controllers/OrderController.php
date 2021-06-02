<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\OrderRepository;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Api\Transformers\Order\OrderTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Http\Controllers\ApiController;

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


    public function storeBuyProduct(Request $request)
    {


        $validator = $this->validatOrderBuyProduct($request);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->respond($errors, $errors->first(), ErrorCode::ERR422);
        }

        $result = $this->orderRepo->placeMultipleOrderBuyProduct($request, Auth::user());
        if ($result === true) {
            return $this->respond(null, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
        }
        list ($errorMsg, $errorCode) = $result;
        return $this->respond(null, $errorMsg, $errorCode);

    }
    public function validatOrderBuyProduct(Request $request)
    {
        $rules = [
            'payment_method_id' => 'required',
            'orders.*.products' => 'required',
            'orders.*.products.*.quantity' => 'required',
            'orders.*.products.*.product_id' => 'required',
            'orders.*.shop_id' => 'required',
            'ship_type_id' => 'required',
            'ship_address_id' => 'required',

        ];
        $messages = [
            'payment_method_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'Phương thức thanh toán']),
            'orders.*.products.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'Sản phẩm']),
            'orders.*.products.*.quantity.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'Số lượng']),
            'orders.*.products.*.product_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'Sản phẩm']),
            'orders.*.shop_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'Cửa hàng']),
            'ship_type_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'Hình thức vận chuyển']),
            'ship_address_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'Địa chỉ nhận hàng']),

        ];



        return $this->validate($request, $rules, $messages);

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
        $messages = [];
        $rules = [
            'orders.*.order_type' => ['required', Rule::in(Orders::TYPE_SUA_CHUA, Orders::TYPE_BAO_HANH)],
            'orders.*.quantity' => 'required',

        ];

        $orders = $request->get('orders');
        foreach ($orders as $key => $value) {
            $orderType = $value['order_type'] ?? null;
            $typeOther = $value['type_other'] ?? null;
            if ($orderType == Orders::TYPE_SUA_CHUA && $typeOther) {
                $rules['orders.' . $key . '.product_title'] = 'required';
                $rules['orders.' . $key . '.note'] = 'required';
                $rules['orders.' . $key . '.shop_id'] = 'required';
            } else {
                $rules['orders.' . $key . '.product_id'] = 'required';
            }

            if ($orderType == Orders::TYPE_BAO_HANH) {
                $rules['orders.' . $key . '.note'] = 'required';
            } else {
                $rules['orders.' . $key . '.ship_type_id'] = 'required';
                $rules['orders.' . $key . '.ship_address_id'] = 'required';
            }

            $messages['orders.' . $key . '.order_type.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Loại đơn hàng']);
            $messages['orders.' . $key . '.order_type.in'] = trans('api::messages.validate.value not allow', ['attribute' => 'Loại đơn hàng']);
            $messages['orders.' . $key . '.ship_type_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Hình thức vận chuyển']);
            $messages['orders.' . $key . '.ship_address_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Địa chỉ nhận hàng']);
            $messages['orders.' . $key . '.quantity.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Số lượng']);
            $messages['orders.' . $key . '.product_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Sản phẩm']);
            $messages['orders.' . $key . '.product_title.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Tên dòng máy']);
            $messages['orders.' . $key . '.note.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Mô tả chi tiết lỗi']);
            $messages['orders.' . $key . '.shop_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Cửa hàng']);

        }


        return $this->validate($request, $rules, $messages);

    }


    public function getList(Request $request) {
        $orders = $this->orderRepo->listOrder($request);
        return $this->respond(OrderTransformer::collection($orders), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function getShopDiscountAmount(Request $request) {
        $validator = $this->validateCheckVoucher($request);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->respond($errors, $errors->first(), ErrorCode::ERR422);
        }
        $result = $this->orderRepo->getShopDiscountAmount($request);
        list ($voucherAmount, $resultMsg) = $result;
        if ($voucherAmount === false) {
            return $this->respond(null, $resultMsg, ErrorCode::ERR422);
        }

        return $this->respond($voucherAmount, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

    }

    protected function validateCheckVoucher(Request $request) {
        $messages = [];
        $rules = [
            'voucher_code' => 'required',
            'shop_id' => 'required',
            'products.*.id' => 'required',
            'products.*.quantity' => 'required',

        ];

        $messages['shop_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Cửa hàng']);
        $messages['voucher_code.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Mã giảm giá']);
        $messages['products.*.id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Sản phẩm']);
        $messages['products.*.quantity.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Số lượng']);



        return $this->validate($request, $rules, $messages);
    }

    public function getSystemDiscountAmount(Request $request) {
        $validator = $this->validateCheckSystemVoucher($request);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->respond($errors, $errors->first(), ErrorCode::ERR422);
        }
        $result = $this->orderRepo->getSystemDiscountAmount($request);
        list ($voucherAmount, $resultMsg) = $result;
        if ($voucherAmount === false) {
            return $this->respond(null, $resultMsg, ErrorCode::ERR422);
        }

        return $this->respond($voucherAmount, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

    }



    protected function validateCheckSystemVoucher(Request $request) {
        $messages = [];
        $rules = [
            'voucher_code' => 'required',
            'products.*.id' => 'required',
            'products.*.quantity' => 'required',

        ];

        $messages['voucher_code.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Mã giảm giá']);
        $messages['products.*.id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Sản phẩm']);
        $messages['products.*.quantity.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Số lượng']);



        return $this->validate($request, $rules, $messages);
    }
}
