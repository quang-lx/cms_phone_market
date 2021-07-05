<?php


namespace Modules\Api\Transformers\Home;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\MediaShortTransformer;

class HomeProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
	        'sale_price' => $this->price - ($this->sale_price * $this->price)/100,
	        'discount' => $this->sale_price,
	        'thumbnail' => $this->thumbnail?  new MediaShortTransformer($this->thumbnail): null

        ];


        return $data;
    }

}
