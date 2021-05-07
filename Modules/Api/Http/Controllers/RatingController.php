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
use Modules\Api\Repositories\RatingRepository;
use Modules\Api\Repositories\RatingShopRepository;
use Modules\Api\Transformers\RatingShopTransformer;
use Modules\Api\Transformers\RatingTransformer;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\Rating;
use Modules\Mon\Entities\RatingShop;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class RatingController extends ApiController
{
	/** @var RatingRepository */
	public $ratingRepo;

	/** @var RatingShopRepository */
	public $ratingShopRepo;

	public function __construct(Authentication $auth, RatingRepository $ratingRepo, RatingShopRepository $ratingShopRepo) {
		parent::__construct($auth);
		$this->ratingRepo = $ratingRepo;
		$this->ratingShopRepo = $ratingShopRepo;
	}

    public function store(Request $request)
    {


        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->respond([], ErrorCode::ERR31_MSG, ErrorCode::ERR31);
        }

        $rating = $request->get('rating');
        $checkExist = Rating::query()->where([
            'product_id' => $request->get('product_id'),
            'user_id' => $user->id,
        ])->exists();
        if (($rating && !in_array($rating, [1, 2, 3, 4, 5])) || $checkExist) {
            return $this->respond([], ErrorCode::ERR32_MSG, ErrorCode::ERR32);
        }

        $data = $request->only('product_id', 'rating', 'comment', 'parent_id', 'files');

        $model = $this->ratingRepo->create(array_merge($data, ['user_id' => $user->id]));

        event(new ProductRatingCreated($request['product_id']));
        return $this->respond($model, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function listByProduct(Request $request, $product_id)
    {
        $data = RatingTransformer::collection($this->ratingRepo->listByProductId($request, $product_id));
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

	public function storeShop(Request $request)
	{


		$user = Auth::user();

		$validator = Validator::make($request->all(), [
			'comment' => 'required',
		]);
		if($validator->fails()){
			return $this->respond([], ErrorCode::ERR31_MSG, ErrorCode::ERR31);
		}

		$rating  = $request->get('rating');
		$checkExist = RatingShop::query()->where([
			'shop_id' => $request->get('shop_id'),
			'user_id' => $user->id,
		])->exists();
		if(($rating && !in_array($rating, [1,2,3,4,5])) || $checkExist){
			return $this->respond([], ErrorCode::ERR32_MSG, ErrorCode::ERR32);
		}

		$data = $request->only('shop_id', 'rating', 'comment', 'parent_id', 'files');

		$model = $this->ratingShopRepo->create(array_merge($data, ['user_id' => $user->id]));
		event(new ShopRatingCreated($request['shop_id']));

		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}

	public function listByShop(Request $request, $shop_id) {
		$data = RatingShopTransformer::collection($this->ratingShopRepo->listByShopId($request, $shop_id));
		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
}
