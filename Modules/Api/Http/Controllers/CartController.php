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
use Illuminate\Validation\Rule;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\CartRepository;
use Modules\Api\Repositories\CategoryRepository;
use Modules\Api\Repositories\OrderRepository;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Api\Transformers\CartTransformer;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\Cart;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartController extends ApiController {

	/** @var CartRepository */
	public $cartRepo;

	public function __construct(Authentication $auth, CartRepository $cartRepo) {
		parent::__construct($auth);

		$this->cartRepo = $cartRepo;
	}

	public function store(Request $request) {


		$validator = $this->validateStore($request);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->respond($errors, $errors->first(), ErrorCode::ERR422);
		}

		$result = $this->cartRepo->updateCart($request, Auth::user());
		if ($result instanceof Cart) {
			return $this->respond(new CartTransformer($result), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
		}
		list ($errorMsg, $errorCode) = $result;
		return $this->respond(null, $errorMsg, $errorCode);

	}


	public function validateStore(Request $request) {
		$messages = [
			'products.*.product_id.required' => trans('api::messages.validate.attribute is required', [ 'attribute' => 'Sản phẩm' ]),
			'products.*.quantity.required' => trans('api::messages.validate.attribute is required', [ 'attribute' => 'Số lượng' ]),
		];

		$rules = [
			'products.*.product_id' => 'required',
			'products.*.quantity' => 'required',

		];
		return $this->validate($request, $rules, $messages);

	}
}
