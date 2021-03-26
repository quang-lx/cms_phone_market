<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class DistrictTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'province_id' => $this->province_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'code' => $this->code,
            'type' => $this->type,
            'province'=>$this->province['name'],
            'urls' => [
                'delete_url' => route('api.district.destroy', $this->id),
            ],

        ];
        return $data;
    }


}
