<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RankTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
	        'point' => $this->point,
	        'max_point' => $this->max_point,
        ];


        return $data;
    }

}
