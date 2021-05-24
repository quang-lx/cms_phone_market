<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Api\Entities\ErrorCode;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\AreaRepository;
use Modules\Api\Repositories\CartRepository;
use Modules\Api\Repositories\OrderRepository;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Mon\Entities\Address;
use Modules\Mon\Entities\Cart;
use Modules\Mon\Entities\CartProduct;
use Modules\Mon\Entities\District;
use Modules\Mon\Entities\OrderProduct;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\Phoenix;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\ProductAttributeValue;
use Modules\Mon\Entities\Province;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\Shop;
use Modules\Mon\Entities\User;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class EloquentCartRepository implements CartRepository {
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;


	public function __construct($model) {
		$this->model = $model;

	}
	public function getCart(Request $request, User $user) {
		$cart = $this->createOrGetCart($user->id);
		return $cart;
	}
	protected function createOrGetCart($userId) {
		$cart = $this->model->where('user_id', $userId)->with('cartProducts')->first();
		if (!$cart) {
			$cart = $this->model->create([
				'user_id' => $userId
			]);
		}
		return $cart;
	}
	public function updateCart(Request $request, User $user) {
		DB::beginTransaction();
		try {

			$cart = $this->createOrGetCart($user->id);
			if (!$cart) {
				throw new InternalErrorException('Tạo giở không thành công');
			}
			$products = $request->get('products');
			if ($products && is_array($products)) {
				foreach ($products as $productData) {
					$quantity = $productData['quantity']?? 0;

					// neu quantity = 0 xoa sp khoi gio hang
					if (!$quantity) {
						CartProduct::query()->where('cart_id', $cart->id)
							->where('product_id', $productData['product_id'])->delete();
						continue;
					}

					$note = $productData['note']?? '';
					$product = Product::find($productData['product_id']);
					// validate product
					if (!$product) {
						DB::rollBack();
						return [ trans('api::messages.order.data invalid'), ErrorCode::ERR422 ];
					}
					$productAttributeValue = null;
					if (isset($productData['product_attribute_value_id']) && !empty($productData['product_attribute_value_id'])) {
						$productAttributeValue = ProductAttributeValue::find($productData['product_attribute_value_id']);
						if (!$productAttributeValue) {
							DB::rollBack();
							return [ trans('api::messages.order.data invalid'), ErrorCode::ERR422 ];
						}
						if ($productAttributeValue->amount < $quantity) {
							DB::rollBack();
							return [ trans('api::messages.order.product out of stock', [ 'name' => $product->name ]), ErrorCode::ERR422 ];
						}
					} else {
						if ($product->amount < $quantity) {
							DB::rollBack();
							return [ trans('api::messages.order.product out of stock', [ 'name' => $product->name ]), ErrorCode::ERR422 ];
						}
					}
					$this->addOrUpdateProduct( $cart, $product, $productAttributeValue, $quantity, $note);
				}
			}
			DB::commit();
			return $cart;
		} catch (\Exception $exception) {
			Log::info($exception->getMessage());
			DB::rollBack();
			return [ trans('api::messages.order.internal server error'), ErrorCode::ERR500 ];
		}
	}

	protected function addOrUpdateProduct(Cart $cart, Product $product, $productAttributeValue, $quantity, $note) {
		$cartProduct = CartProduct::query()->where('cart_id', $cart->id)
			->where('product_id', $product->id)->first();
		if ($cartProduct) {
			$cartProduct->quantity = $quantity;
			$cartProduct->note = $note;
			$cartProduct->company_id = $product->company_id;
			$cartProduct->shop_id = $product->shop_id;
			$cartProduct->product_attribute_value_id = $productAttributeValue? $productAttributeValue->id : null;
			$cartProduct->save();
			return $cartProduct;
		}
		 return CartProduct::create([
			 'cart_id' => $cart->id,

			 'product_id' => $product->id,
			 'shop_id' => $product->shop_id,
			 'shop_name' => optional($product->shop)->name,
			 'company_id' => $product->company_id,
			 'product_attribute_value_id' => $productAttributeValue? $productAttributeValue->id : null,
			 'quantity' => $quantity,

			 'note' => $note,
		 ]);
	}
}
