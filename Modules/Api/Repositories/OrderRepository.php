<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;
use Modules\Mon\Entities\Address;
use Modules\Mon\Entities\District;
use Modules\Mon\Entities\Phoenix;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\ProductAttributeValue;
use Modules\Mon\Entities\Province;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\User;

interface OrderRepository
{
	public function placeMultipleOrder (Request $request, User $user);
	public function placeMultipleOrderBuyProduct (Request $request, User $user);
	public function placeOrder($requestParams, User $user, ShipType $shipType, Address $shipAddress, Province $province, District $district, Phoenix $phoenix,  Product $product,  $productAttributeValue);
	public function listOrder(Request $request);
	public function getDetail($id);
	public function getShopDiscountAmount(Request $request);
	public function getSystemDiscountAmount(Request $request);
 }
