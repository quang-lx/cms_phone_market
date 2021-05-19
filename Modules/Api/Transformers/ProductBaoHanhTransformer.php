<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductBaoHanhTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
	        'price' => $this->price,
	        'sale_price' => $this->price - ($this->sale_price * $this->price)/100,
	        'discount' => $this->sale_price,
	        'guarantee_time' => now()->addMonth()->format('d/m/Y'),
	        'guarantee_status' => '',
	        'thumbnail' => $this->thumbnail?  new MediaShortTransformer($this->thumbnail): null

        ];


        return $data;
    }

}
