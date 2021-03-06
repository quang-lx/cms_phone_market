<?php

namespace Modules\Api\Http\Controllers;

use App\Events\OrderStatusUpdated;
use App\Events\ShopNotiCreated;
use App\Events\ShopUpdateOrderStatus;
use App\Events\UserUpdateOrderStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\OrderRepository;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Api\Transformers\Order\OrderFullTransformer;
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
			'payment_method_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'Ph????ng th???c thanh to??n']),
			'orders.*.products.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'S???n ph???m']),
			'orders.*.products.*.quantity.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'S??? l?????ng']),
			'orders.*.products.*.product_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'S???n ph???m']),
			'orders.*.shop_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'C???a h??ng']),
			'ship_type_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => 'H??nh th???c v???n chuy???n']),
			'ship_address_id.required' => trans('api::messages.validate.attribute is required', ['attribute' => '?????a ch??? nh???n h??ng']),

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

			$messages['orders.' . $key . '.order_type.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Lo???i ????n h??ng']);
			$messages['orders.' . $key . '.order_type.in'] = trans('api::messages.validate.value not allow', ['attribute' => 'Lo???i ????n h??ng']);
			$messages['orders.' . $key . '.ship_type_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'H??nh th???c v???n chuy???n']);
			$messages['orders.' . $key . '.ship_address_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => '?????a ch??? nh???n h??ng']);
			$messages['orders.' . $key . '.quantity.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'S??? l?????ng']);
			$messages['orders.' . $key . '.product_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'S???n ph???m']);
			$messages['orders.' . $key . '.product_title.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'T??n d??ng m??y']);
			$messages['orders.' . $key . '.note.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'M?? t??? chi ti???t l???i']);
			$messages['orders.' . $key . '.shop_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'C???a h??ng']);

		}


		return $this->validate($request, $rules, $messages);

	}


	public function getList(Request $request)
	{
		$orders = $this->orderRepo->listOrder($request);
		return $this->respond(OrderTransformer::collection($orders), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
	public function getDetail(Request $request, $id)
	{
		$order = $this->orderRepo->getDetail($id);
		if (!$order || $order->user_id != Auth::user()->id) {
			return $this->respond(null, ErrorCode::ERR33_MSG, ErrorCode::ERR422);
		}
		return $this->respond(new OrderFullTransformer($order), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
	public function getShopDiscountAmount(Request $request)
	{
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

	protected function validateCheckVoucher(Request $request)
	{
		$messages = [];
		$rules = [
			'voucher_code' => 'required',
			'shop_id' => 'required',
			'products.*.id' => 'required',
			'products.*.quantity' => 'required',

		];

		$messages['shop_id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'C???a h??ng']);
		$messages['voucher_code.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'M?? gi???m gi??']);
		$messages['products.*.id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'S???n ph???m']);
		$messages['products.*.quantity.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'S??? l?????ng']);


		return $this->validate($request, $rules, $messages);
	}

	public function getSystemDiscountAmount(Request $request)
	{
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


	protected function validateCheckSystemVoucher(Request $request)
	{
		$messages = [];
		$rules = [
			'voucher_code' => 'required',
			'products.*.id' => 'required',
			'products.*.quantity' => 'required',

		];

		$messages['voucher_code.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'M?? gi???m gi??']);
		$messages['products.*.id.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'S???n ph???m']);
		$messages['products.*.quantity.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'S??? l?????ng']);


		return $this->validate($request, $rules, $messages);
	}

	public function updateStatus(Request $request, Orders $order)
	{
		$user = Auth::user();
		$validator = $this->validateStatusRequest($request);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->respond($errors, $errors->first(), ErrorCode::ERR422);
		}
		$oldStatus = $order->status;
		$orderType = $order->order_type;

		$newStatus = $request->get('new_status');

		$validOrderStatus = Orders::getValidNextStatus();
		if (isset($validOrderStatus[$orderType][$oldStatus]) && in_array($newStatus, $validOrderStatus[$orderType][$oldStatus])) {
			try {
				DB::beginTransaction();
				$order->status = $newStatus;
				$order->save();
				event(new OrderStatusUpdated([
					'order_id' => $order->id,
					'order_type' => $order->order_type,
					'title' => $order->getStatusNameAttribute($newStatus),
					'old_status' => $oldStatus,
					'new_status' => $newStatus,
					'user_id' => Auth::user()->id,
					'shop_id' => null,
					'note' => $request->get('note')
				]));

				$shopNotiArr = config(sprintf('shopnoti.shop_notifications.%s.%s', $order->order_type, $order->status), null);

				if ($shopNotiArr && is_array($shopNotiArr)) {
					event(new UserUpdateOrderStatus([
						'order_id' => $order->id,
						'title' => $shopNotiArr['title'],
						'content' => sprintf($shopNotiArr['content'], $order->id),
						'shop_id' => $order->shop_id,
						'order_status' => $order->status,
						'order_type' => $order->order_type,
					]));
				}
				if ($newStatus == Orders::STATUS_ORDER_DONE) {
					$data = [
						'title' => trans(sprintf('order.notifications.%s.title', $order->order_type)),
						'content' => trans(sprintf('order.notifications.%s.content done', $order->order_type), ['order_code' => $order->id]),
						'fcm_token' => $user->fcm_token,
						'order_id' => $order->id,
						'type' => trans(sprintf('order.notifications.%s.type', $order->order_type), ['order_status' => Orders::STATUS_ORDER_DONE]),
					];

					event(new ShopNotiCreated($data));
					event(new ShopUpdateOrderStatus([
						'title' => trans(sprintf('order.notifications.%s.title', $order->order_type)),
						'content' => trans(sprintf('order.notifications.%s.content done', $order->order_type), ['order_code' => $order->id]),
						'noti_type' => trans(sprintf('order.notifications.%s.type', $order->order_type), ['order_status' => Orders::STATUS_ORDER_DONE]),

						'user_id' => $order->user_id,
						'order_id' => $order->id
					]));
				}
				if ($order->order_type == Orders::TYPE_MUA_HANG && $newStatus = Orders::STATUS_ORDER_DONE) {
					$orderProducts = $order->allOrderProducts;
					foreach ($orderProducts as $orderProduct) {
						$orderProduct->warranty_time = Carbon::now()->addMonths($orderProduct->product->warranty_time);
						$orderProduct->save();
					}
				}
				DB::commit();
				return $this->respond([], ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
			} catch (\Exception $exception) {
				Log::error($exception->getMessage());
				DB::rollBack();
			}

		} else {
			$order->status = $newStatus;
			return $this->respond([], trans('api::messages.validate.status not allow update', ['status' => $order->getStatusNameAttribute($newStatus)]), ErrorCode::ERR422);
		}
	}

	protected function validateStatusRequest(Request $request)
	{
		$messages = [];
		$rules = [
			'new_status' => [
				'required',
				Rule::in([
					Orders::STATUS_ORDER_CREATED,
					Orders::STATUS_ORDER_WAIT_CLIENT_CONFIRM,
					Orders::STATUS_ORDER_CONFIRMED,
					Orders::STATUS_ORDER_SENDING,
					Orders::STATUS_ORDER_FIXING,
					Orders::STATUS_ORDER_WARRANTING,
					Orders::STATUS_ORDER_DONE,
					Orders::STATUS_ORDER_CANCEL,
				])
				]

		];

		$messages['new_status.required'] = trans('api::messages.validate.attribute is required', ['attribute' => 'Tr???ng th??i ????n h??ng']);
		$messages['new_status.in'] = trans('api::messages.validate.value not allow', ['attribute' => 'Tr???ng th??i ????n h??ng']);

		return $this->validate($request, $rules, $messages);
	}
}
