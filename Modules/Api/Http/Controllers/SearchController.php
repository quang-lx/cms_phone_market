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
use Modules\Api\Repositories\SearchRepository;
use Modules\Api\Transformers\ProductTransformer;
use Modules\Api\Transformers\Search\SearchShopTransformer;
use Modules\Api\Transformers\UserTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\SmsToken;
use Modules\Mon\Entities\User;
use Modules\Mon\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;

class SearchController extends ApiController
{

    /** @var SearchRepository */
    public $searchRepo;

    public function __construct(Authentication $auth, SearchRepository $searchRepo)
    {
        parent::__construct($auth);

        $this->searchRepo = $searchRepo;
    }
    public function listSuggestion(Request $request)
    {
        $data = $this->searchRepo->listSuggestion($request);
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
	public function search(Request $request)
	{
		list($shop, $products) = $this->searchRepo->search($request);
		$data = [
			'shop' => $shop? new SearchShopTransformer($shop): null,
			'products' => $products? ProductTransformer::collection($products): null,
		];
        if ($products->count() && $request->get('q')) {
            $user = Auth::user();
            insert_user_search(optional($user)->id, $request->header('fcm_token'), $products);
        }
		return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
	}
}
