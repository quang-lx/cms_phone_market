<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoenixesTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,
        ];


        return $data;
    }

}
