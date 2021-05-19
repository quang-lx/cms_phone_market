<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipTypeTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'name' => $this->name,
	        'price' => 0

        ];


        return $data;
    }

}
