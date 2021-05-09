<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\ApiShopRepository;
use Modules\Api\Transformers\ShopFullTransformer;
use Modules\Api\Transformers\ShopTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class ShopController extends ApiController
{
    /** @var ApiShopRepository */
    public $apiShopRepository;

    public function __construct(Authentication $auth, ApiShopRepository $apiShopRepository)
    {
        parent::__construct($auth);

        $this->apiShopRepository = $apiShopRepository;
    }

    public function detail(Request $request, $id)
    {
        $shop = $this->apiShopRepository->detail($request, $id);
        $data = $shop ? new ShopFullTransformer($shop) : null;
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function listSuggestion(Request $request)
    {
        $data = $this->apiShopRepository->listSuggestion($request);
        return $this->respond($data, ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
    public function search(Request $request)
    {


        $shops = $this->apiShopRepository->getList($request);
        return $this->respond(ShopTransformer::collection($shops), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    public function shopNearest(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respond([], ErrorCode::ERR19_MSG, ErrorCode::ERR19);
        }
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $shops = $this->apiShopRepository->getShopNearest($lat, $lng);
        return $this->respond(ShopTransformer::collection($shops), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }

    //TODO
    public function shopBaoHanh(Request $request)
    {
        $user = $this->auth->user();
        $shops = $this->apiShopRepository->getShopBaoHanh($request, $user);
        return $this->respond(ShopTransformer::collection($shops), ErrorCode::SUCCESS_MSG, ErrorCode::SUCCESS);
    }
}
