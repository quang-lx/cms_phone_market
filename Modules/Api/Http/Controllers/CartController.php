<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\CartRepository;
use Modules\Api\Transformers\Cart\CartTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\Cart;
use Modules\Mon\Http\Controllers\ApiController;

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

	public function detail(Request $request) {
		$cart = $this->cartRepo->getCart($request, Auth::user());
		return $this->respond($cart ? new CartTransformer($cart) : null, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);


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
