<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CartProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [

            'quantity' => $this->quantity,
            'note' => $this->note,
             'product' => new ProductTransformer($this->product),
             'product_attribute' => $this->productValue,
        ];


        return $data;
    }

}
