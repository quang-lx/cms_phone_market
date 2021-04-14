<?php


namespace Modules\Api\Transformers\Home;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\MediaShortTransformer;

class HomePCategoryTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
	        'name' => $this->name,
	        'type' => $this->type,
	        'type_name' => $this->type_name,
	        'thumbnail' => $this->thumbnail?  new MediaShortTransformer($this->thumbnail): null

        ];


        return $data;
    }

}
