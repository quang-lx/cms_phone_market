<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;
use Modules\Mon\Entities\Address;
use Modules\Mon\Entities\Cart;
use Modules\Mon\Entities\District;
use Modules\Mon\Entities\Phoenix;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\ProductAttributeValue;
use Modules\Mon\Entities\Province;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\User;

interface CartRepository
{
	public function updateCart (Request $request, User $user);
	public function getCart (Request $request, User $user);
}
