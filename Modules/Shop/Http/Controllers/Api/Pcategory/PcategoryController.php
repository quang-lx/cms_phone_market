<?php

namespace Modules\Shop\Http\Controllers\Api\Pcategory;

use Illuminate\Http\Request;
use Modules\Shop\Repositories\PcategoryRepository;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;

class PcategoryController extends ApiController
{
    /**
     * @var PcategoryRepository
     */
    private $pcategoryRepository;

    public function __construct(Authentication $auth, PcategoryRepository $pcategory)
    {
        parent::__construct($auth);

        $this->pcategoryRepository = $pcategory;
    }

	public function tree(Request $request)
	{
		return $this->pcategoryRepository->serverPagingForTree($request);
	}
}
