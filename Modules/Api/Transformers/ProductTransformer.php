<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'discount' => 15,
	        'thumbnail' => $this->thumbnail?  new MediaShortTransformer($this->thumbnail): null

        ];


        return $data;
    }

}
