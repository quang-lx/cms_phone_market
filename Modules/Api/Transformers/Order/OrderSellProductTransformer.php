<?php


namespace Modules\Api\Transformers\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\MediaShortTransformer;

class OrderSellProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'note' => $this->note,
            'product_title' => $this->product_title,
            'product_id' => $this->product_id,
            'product_attribute_value_id' => $this->product_attribute_value_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'price_sale' => $this->price_sale,
            'product_thumbnail' => new MediaShortTransformer($this->product->files->first())
        ];


        return $data;
    }

}
