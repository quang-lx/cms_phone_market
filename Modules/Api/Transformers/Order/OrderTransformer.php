<?php


namespace Modules\Api\Transformers\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\Shop;

class OrderTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product' => $this->getOrderProduct($this->order_type, $this->type_other,$this->allOrderProducts)
        ];


        return $data;
    }

    public function getOrderProduct($type, $otherType, $orderProducts) {
    	$firstProduct = $orderProducts->first();
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
