<?php


namespace Modules\Api\Transformers\Search;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\MediaShortTransformer;

class SearchShopTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
	        'id' => $this->id,
	        'name' => $this->name,
	        'address' => $this->address,
	        'thumbnail' => $this->thumbnail?  new MediaShortTransformer($this->thumbnail): null

        ];


        return $data;
    }

}
