<?php


namespace Modules\Api\Transformers\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\ShopTransformer;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\Shop;

class OrderTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
	        'total_price' => $this->total_price,
	        'pay_price' => $this->pay_price,
	        'ship_fee' => $this->ship_fee,
	        'discount' => $this->discount,
	        'order_type' => $this->order_type,
	        'status' => $this->status,
            'product' => $this->getOrderProduct($this->order_type, $this->type_other,$this->firstOrderProduct),
            'shop' => new ShopTransformer($this->shop)
        ];


        return $data;
    }

    public function getOrderProduct($type, $otherType, $firstProduct) {
        switch ($type) {
            case Orders::TYPE_BAO_HANH:
                return new OrderProductBaoHanhTransformer($firstProduct);
            case Orders::TYPE_SUA_CHUA:
                if ($otherType) {
                    return new OrderProductSuaChuaKhacTransformer($firstProduct);
                }
                return new OrderProductSuaChuaTransformer($firstProduct);
            case Orders::TYPE_MUA_HANG:
                return new OrderSellProductTransformer($firstProduct);
        }
    }


}
