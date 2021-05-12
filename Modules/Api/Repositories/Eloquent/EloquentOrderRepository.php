<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Api\Repositories\OrderRepository;
use Modules\Mon\Entities\Address;
use Modules\Mon\Entities\District;
use Modules\Mon\Entities\Phoenix;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\ProductAttributeValue;
use Modules\Mon\Entities\Province;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\User;

class EloquentOrderRepository   implements OrderRepository
{
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;

	public function __construct($model)
	{
		$this->model = $model;
	}

	public function placeOrder($requestParams, User $user, ShipType $shipType, Address $shipAddress, Province $province, District $district, Phoenix $phoenix, Product $product, ProductAttributeValue $productAttributeValue) {
		$orderData = [];
		$orderProductData = [];
		$orderData['user_id']= $user->id;
		$orderData['company_id'] = $user->company_id;
		$orderData['shop_id'] = $user->shop_id;
		$orderData['ship_type_id'] = $shipType->id;

		// ship
		$orderData['ship_address_id'] = $requestParams['ship_address_id'];
		$orderData['ship_type_id'] = $shipType->id;
		$orderData['ship_address'] = $shipType->address;
		$orderData['ship_province_id'] = $province->id;
		$orderData['ship_province_name'] = $province->name;

		$orderData['ship_district_id'] = $district->id;
		$orderData['ship_district_name'] = $district->name;

		$orderData['ship_phoenix_id'] = $phoenix->id;
		$orderData['ship_phoenix_name'] = $phoenix->name;


		$orderData['status'] = 0;
		$orderData['payment_status'] = 0;
		$orderData['order_type'] =  $requestParams['order_type'];
		$orderData['ship_fee'] =  0;
		$orderData['discount'] =  0;

		$productPrice = $product->price;
		if ($product->price_sale) {
			$productPrice = $productPrice * (100 -$productPrice->price_sale)/ 100;
		}
		$orderProductData['product_attribute_value_id'] = $requestParams['product_attribute_value_id']?? null;
		$orderProductData['quantity'] = $requestParams['quantity'];
		$orderProductData['price'] = $product->price;
		$orderProductData['price_sale'] = $product->price_sale;
		$orderProductData['product_id'] = $product->id;
		$orderProductData['note'] = $requestParams['note'];

		if ($productAttributeValue) {
			$orderProductData['price'] = $productAttributeValue->price;
			$orderProductData['price_sale'] = $productAttributeValue->price_sale;

			$productPrice = $productAttributeValue->price;
			if ($productAttributeValue->price_sale) {
				$productPrice = $productPrice * (100 -$productAttributeValue->price_sale)/ 100;
			}
		}


	}


}
