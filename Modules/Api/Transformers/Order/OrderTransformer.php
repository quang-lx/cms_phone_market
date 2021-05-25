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
            'products' => $this->getOrderProduct($this->order_type, $this->type_other,$this->allOrderProducts)
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
