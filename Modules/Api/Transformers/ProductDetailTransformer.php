<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'discount' => 15,
	        'amount' => $this->amount,
	        'description' => $this->description,
	        'p_weight' => $this->p_weight,
	        's_long' => $this->s_long,
	        's_width' => $this->s_width,
	        's_height' => $this->s_height,
	        'brand_id' => $this->brand_id,
	        'brand_name' => optional($this->brand)->name,
	        'shop' => $this->shop? new ShopTransformer($this->shop): null,

	        'files' => $this->files?  MediaShortTransformer::collection($this->files): null

        ];


        return $data;
    }

}
