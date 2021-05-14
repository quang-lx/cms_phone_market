<?php

namespace Modules\Api\Http\Controllers;

use App\Events\NeedCreateUserSmsToken;
use App\Rules\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Events\ProductRatingCreated;
use Modules\Api\Events\ShopRatingCreated;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\RatingRepository;
use Modules\Api\Repositories\RatingShopRepository;
use Modules\Api\Transformers\AddressTransformer;
use Modules\Api\Transformers\RatingShopTransformer;
use Modules\Api\Transformers\RatingTransformer;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\Address;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\Rating;
use Modules\Mon\Entities\RatingShop;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddressController extends ApiController
{
	/** @var AddressRepository */
	public $addressRepo;


	public function __construct(Authentication $auth, AddressRepository $addressRepo) {
		parent::__construct($auth);
		$this->addressRepo = $addressRepo;
	}

    public function store(Request $request)
    {


        $user = Auth::user();
	    $validator = $this->validateStore($request);
	    if ($validator->fails()) {
		    $errors = $validator->errors();
		    return $this->respond($errors, $errors->first(), ErrorCode::ERR422);
	    }


        $data = $request->only(
	        'fullname',
	        'phone',
	        'address',
	        'province_id',
	        'district_id',
	        'phoenix_id',
	        'province_name',
	        'district_name',
	        'phoenix_name',
	        'default');

        $model = $this->addressRepo->create(array_merge($data, ['user_id' => $user->id]));

        return $this->respond(new AddressTransformer($model), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
	public function update(Request $request, Address $address)
	{


		$user = Auth::user();
		if ($user->id != $address->user_id) {
			return $this->respond(null, trans('api::messages.validate.address not your own') , ErrorCode::ERR422);

		}
		$validator = $this->validateStore($request);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->respond($errors, $errors->first(), ErrorCode::ERR422);
		}

		$data = $request->only(
			'fullname',
			'phone',
			'address',
			'province_id',
			'district_id',
			'phoenix_id',
			'province_name',
			'district_name',
			'phoenix_name',
			'default');

		$model = $this->addressRepo->update($address, $data);

		return $this->respond(new AddressTransformer($model), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}

	public function index(Request $request) {
		$user = Auth::user();
		$addresses = $this->addressRepo->serverPagingFor($request, $user);
		return $this->respond(AddressTransformer::collection($addresses), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

	}

	public function destroy(Request $request, Address $address) {
		$user = Auth::user();
		if ($address->user_id != $user->id) {
			return $this->respond(null, trans('api::messages.validate.address not your own'), ErrorCode::ERR422);
		}
		$this->addressRepo->delete($address);
		return $this->respond(null, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

	}



	public function validateStore(Request $request)
	{
		$rules= [
			'fullname' => 'required',
			'phone' => 'required',
			'province_id' => 'required',
			'district_id' => 'required',
			'phoenix_id' => 'required',
			'province_name' => 'required',
			'district_name' => 'required',
			'phoenix_name' => 'required',
			'address' => 'required',
		];

		$messages = [
			'fullname.required' => trans('attribute is required', ['attribute' => 'Họ tên']),
			'phone.required' => trans('attribute is required', ['attribute' => 'Số điện thoại']),
			'province_id.required' => trans('attribute is required', ['attribute' => 'Tỉnh/Thành phố']),
			'district_id.required' => trans('attribute is required', ['attribute' => 'Quận/Huyện']),
			'phoenix_id.required' => trans('attribute is required', ['attribute' => 'Phường/Xã']),
			'province_name.required' => trans('attribute is required', ['attribute' => 'Tỉnh/Thành phố']),
			'district_name.required' => trans('attribute is required', ['attribute' => 'Quận/Huyện']),
			'phoenix_name.required' => trans('attribute is required', ['attribute' => 'Phường/Xã']),
			'address.required' => trans('attribute is required', ['attribute' => 'Địa chỉ cụ thể']),
		];

		return $this->validate($request, $rules, $messages);

	}
}
