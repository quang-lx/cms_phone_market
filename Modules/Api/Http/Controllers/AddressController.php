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

        $validator = $this->validate($request, [
        	'fullname' => 'required',
        	'phone' => 'required',
        	'province_id' => 'required',
        	'district_id' => 'required',
        	'phoenix_id' => 'required',
        	'address' => 'required',
        ], [
        	'fullname.required' => trans('attribute is required', ['attribute' => 'Họ tên']),
        	'phone.required' => trans('attribute is required', ['attribute' => 'Số điện thoại']),
        	'province_id.required' => trans('attribute is required', ['attribute' => 'Tỉnh/Thành phố']),
        	'district_id.required' => trans('attribute is required', ['attribute' => 'Quận/Huyện']),
        	'phoenix_id.required' => trans('attribute is required', ['attribute' => 'Phường/Xã']),
        	'address.required' => trans('attribute is required', ['attribute' => 'Địa chỉ cụ thể']),
        ]);
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
	        'default');

        $model = $this->addressRepo->create(array_merge($data, ['user_id' => $user->id]));

        return $this->respond(new AddressTransformer($model), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
	public function update(Request $request, Address $address)
	{


		$user = Auth::user();
		if ($user->id != $address->user_id) {
			return $this->respond(null, trans('api.messages.address not your own') , ErrorCode::ERR422);

		}

		$validator = $this->validate($request, [
			'fullname' => 'required',
			'phone' => 'required',
			'province_id' => 'required',
			'district_id' => 'required',
			'phoenix_id' => 'required',
			'address' => 'required',
		], [
			'fullname.required' => trans('api.messages.attribute is required', ['attribute' => 'Họ tên']),
			'phone.required' => trans('api.messages.attribute is required', ['attribute' => 'Số điện thoại']),
			'province_id.required' => trans('api.messages.attribute is required', ['attribute' => 'Tỉnh/Thành phố']),
			'district_id.required' => trans('api.messages.attribute is required', ['attribute' => 'Quận/Huyện']),
			'phoenix_id.required' => trans('api.messages.attribute is required', ['attribute' => 'Phường/Xã']),
			'address.required' => trans('api.messages.attribute is required', ['attribute' => 'Địa chỉ cụ thể']),
		]);
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
			'default');

		$model = $this->addressRepo->update($address, $data);

		return $this->respond(new AddressTransformer($model), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}

	public function index(Request $request) {
		$user = Auth::user();
		$addresses = $this->addressRepo->serverPagingFor($request, $user);
		return $this->respond(AddressTransformer::collection($addresses), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);

	}

}