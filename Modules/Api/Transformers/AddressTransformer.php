<?php

namespace Modules\Api\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class AddressTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'phone' => $this->phone,
            'address' => $this->address,
            'default' => $this->default,
	        'province_id'=> $this->province_id,
	        'district_id'=> $this->district_id,
	        'phoenix_id'=> $this->phoenix_id,

        ];


        return $data;
    }




}
