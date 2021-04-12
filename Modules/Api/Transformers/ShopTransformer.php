<?php

namespace Modules\Api\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class ShopTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'status' => $this->status,

            'lat' => $this->lat,
            'lng' => $this->lng,
            'place' => $this->place,
            'thumbnail' => $this->thumbnail?  new MediaShortTransformer($this->thumbnail): null


        ];


        return $data;
    }


}
