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
use Modules\Api\Repositories\RatingRepository;
use Modules\Api\Transformers\RatingTransformer;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\Rating;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class RatingController extends ApiController
{
	/** @var RatingRepository */
	public $ratingRepo;

	public function __construct(Authentication $auth, RatingRepository $ratingRepo) {
		parent::__construct($auth);
		$this->ratingRepo = $ratingRepo;
	}

	public function store(Request $request)
	{


        $user = Auth::user();

		$validator = Validator::make($request->all(), [
			'comment' => 'required',
		]);
		if($validator->fails()){
			return $this->respond([], ErrorCode::ERR31_MSG, ErrorCode::ERR31);
		}

		$rating  = $request->get('rating');
		$checkExist = Rating::query()->where([
			'product_id' => $request->get('product_id'),
			'user_id' => $user->id,
		])->exists();
		if(($rating && !in_array($rating, [1,2,3,4,5])) || $checkExist){
			return $this->respond([], ErrorCode::ERR32_MSG, ErrorCode::ERR32);
		}

		$request = $request->only('product_id', 'rating', 'comment', 'parent_id', 'files');

        $data = $this->ratingRepo->create(array_merge($request, ['user_id' => $user->id]));
		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}

	public function listByProduct(Request $request, $product_id) {
		$data = RatingTransformer::collection($this->ratingRepo->listByProductId($request, $product_id));
		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
}
