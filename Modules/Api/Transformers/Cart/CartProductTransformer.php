<?php


namespace Modules\Api\Transformers\Cart;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\ProductTransformer;

class CartProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [

            'quantity' => $this->product->count(),
            'note' => $this->note,
             'product' => new ProductTransformer($this->product),
             'product_attribute' => $this->productValue,
        ];


        return $data;
    }

}
