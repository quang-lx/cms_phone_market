<?php

namespace Modules\Shop\Http\Controllers\Api\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Shop;
use Modules\Shop\Transformers\ShopTransformer;
use Modules\Shop\Repositories\ShopRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;
use Illuminate\Support\Facades\Auth;

class DashboardController extends ApiController
{
    /**
     * @var ShopRepository
     */
    private $shopRepository;

    public function __construct(Authentication $auth, ShopRepository $shop)
    {
        parent::__construct($auth);

        $this->shopRepository = $shop;
    }


    public function listShop(Request $request)
    {
        return $this->shopRepository->getListShop($request);
    }


}
