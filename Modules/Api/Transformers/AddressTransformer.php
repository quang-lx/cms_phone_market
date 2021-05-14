<?php

namespace Modules\Api\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Repositories\AreaRepository;


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
	        'province_name'=> $this->province_name,
	        'district_name'=> $this->district_name,
	        'phoenix_name'=> $this->phoenix_name,

        ];


        return $data;
    }




}
