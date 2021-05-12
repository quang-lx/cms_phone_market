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

	/** @var ShipTypeRepository */
	public $shipTypeRepo;

	/** @var AreaRepository */
	public $areaRepo;

	/** @var AddressRepository */
	public $addressRepo;
	public function __construct(Authentication $auth, OrderRepository $orderRepo, ShipTypeRepository $shipTypeRepo, AreaRepository $areaRepo, AddressRepository $addressRepo)
	{
		parent::__construct($auth);

		$this->orderRepo = $orderRepo;
		$this->shipTypeRepo = $shipTypeRepo;
		$this->areaRepo = $areaRepo;
		$this->addressRepo = $addressRepo;
	}
	public function store(Request $request) {


		$validator = $this->validateStore($request);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->respond($errors, $errors->first(), ErrorCode::ERR422);
		}

		$shipType = $this->shipTypeRepo->findById($request, $request->get('ship_type_id'));
		if (!$shipType) {
			return $this->respond(null, trans('api.messages.order.data invalid'), ErrorCode::ERR422);
		}

		$shipAddress = $this->addressRepo->findById($request, $request->get('ship_address_id'));
		if (!$shipAddress) {
			return $this->respond(null, trans('api.messages.order.data invalid'), ErrorCode::ERR422);
		}

		$shipProvince = $this->getShipProvince($shipAddress->province_id);
		if (!$shipProvince) {
			return $this->respond(null, trans('api.messages.order.data invalid'), ErrorCode::ERR422);
		}

		$request->request->add(['province_id' => $shipAddress->province_id, 'district_id' => $shipAddress->phoenix_id]);

		$shipDistrict = $this->getShipDistrict($request, $shipAddress->district_id);
		if (!$shipDistrict) {
			return $this->respond(null, trans('api.messages.order.data invalid'), ErrorCode::ERR422);
		}

		$shipPhoenix = $this->getShipPhoenix($request, $shipAddress->phoenix_id);
		if (!$shipPhoenix) {
			return $this->respond(null, trans('api.messages.order.data invalid'), ErrorCode::ERR422);
		}


	}

	protected function getShipProvince($province_id) {
		$provinces = $this->areaRepo->getProvinces();
		return $provinces->where('id', $province_id)->first();
	}

	protected function getShipDistrict(Request $request, $district_id) {
		$districts = $this->areaRepo->getDistricts($request);
		return $districts->where('id', $district_id)->first();
	}

	protected function getShipPhoenix(Request $request, $phoenix_id) {
		$phoenixs = $this->areaRepo->getPhoenixes($request);
		return $phoenixs->where('id', $phoenix_id)->first();
	}

	public function validateStore(Request $request) {
		$orderType = $request->get('type');
		$rules = [];
		$messages = [];
		$rules = [
			'order_type' => 'required',
			'ship_type_id' => 'required',
			'ship_address_id' => 'required',
			'quantity' => 'required',
		];
		$messages = [
			'type.required' => trans('api.messages.attribute is required', ['attribute' => 'Loại đơn hàng']),
			'ship_type_id.required' => trans('api.messages.attribute is required', ['attribute' => 'Hình thức vận chuyển']),
			'ship_address_id.required' => trans('api.messages.attribute is required', ['attribute' => 'Địa chỉ nhận hàng']),
			'quantity.required' => trans('api.messages.attribute is required', ['attribute' => 'Số lượng']),
		];


		if ($orderType == Orders::TYPE_SUA_CHUA) {

		}
		return $this->validate($request, $rules, $messages);

	}
}
