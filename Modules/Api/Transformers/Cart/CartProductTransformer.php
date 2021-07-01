<?php


namespace Modules\Api\Transformers\Cart;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\ProductTransformer;

class CartProductTransformer extends JsonResource {


	public function toArray($request) {
		$data = [

			'quantity' => $this->quantity,
			'note' => $this->note,
			'product_attribute_value_id' => $this->product_attribute_value_id,
			'product' => new ProductTransformer($this->product),
			'product_attribute' => $this->productValue ? $this->getProductAttribute($this->productValue) : null,
		];


		return $data;
	}

	public function getProductAttribute($productAttribute) {
		return [
			'product_attribute_value_id' => $productAttribute->id,
			'price' => $productAttribute->price,
			'sale_price' => $productAttribute->price - ($productAttribute->price * $productAttribute->sale_price / 100),

		];
	}

}
