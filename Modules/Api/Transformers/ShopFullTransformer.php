<?php

namespace Modules\Api\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class ShopFullTransformer extends JsonResource
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
	        'rating_avg' => $this->rating_avg,
	        'rating_user' => $this->rating_user,
	        'product_number' => $this->products->count(),
	        'time_offline' => '20 phút trước',
            'thumbnail' => $this->thumbnail?  new MediaShortTransformer($this->thumbnail): null


        ];


        return $data;
    }






}
