<?php


namespace Modules\Api\Transformers\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\ShipTypeTransformer;
use Modules\Api\Transformers\ShopTransformer;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\Shop;

class OrderFullTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_type' => $this->order_type,
            'status' => $this->status,
	        'total_price' => $this->total_price,
	        'pay_price' => $this->pay_price,
	        'ship_fee' => $this->ship_fee,
	        'discount' => $this->discount,
	        'ship_address' => $this->ship_address,
	        'ship_fullname' => $this->ship_fullname,
	        'ship_phone' => $this->ship_phone,
	        'ship_type' => new ShipTypeTransformer($this->shipType),
	        'payment_method' => $this->paymentHistory? new PaymentHistoryTransformer($this->paymentHistory): null,
            'products' => $this->getOrderProduct($this->order_type, $this->type_other,$this->allOrderProducts),
            'shop' => new ShopTransformer($this->shop)

        ];


        return $data;
    }

	public function getOrderProduct($type, $otherType, $orderProducts) {
		switch ($type) {
			case Orders::TYPE_BAO_HANH:
				return OrderProductBaoHanhTransformer::collection($orderProducts);
			case Orders::TYPE_SUA_CHUA:
				if ($otherType) {
					return OrderProductSuaChuaKhacTransformer::collection($orderProducts);
				}
				return OrderProductSuaChuaTransformer::collection($orderProducts);
			case Orders::TYPE_MUA_HANG:
				return OrderSellProductTransformer::collection($orderProducts);
		}
	}



}
