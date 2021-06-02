<?php

namespace Modules\Admin\Http\Controllers\Api\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Mon\Entities\Shop;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Admin\Repositories\ShopRepository;

class ShopController extends ApiController
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


    public function statistical(Request $request)
    {
        return  $this->shopRepository->statistical($request);
    }

    
}
