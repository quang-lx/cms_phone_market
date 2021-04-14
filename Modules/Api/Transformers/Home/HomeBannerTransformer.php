<?php


namespace Modules\Api\Transformers\Home;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\MediaShortTransformer;

class HomeBannerTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
	        'description' => $this->description,
	        'thumbnail' => $this->thumbnail?  new MediaShortTransformer($this->thumbnail): null

        ];


        return $data;
    }

}
