<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class PhoenixTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'district_id' => $this->district_id,
            'code' => $this->code,
            'type' => $this->type,
            'district'=>$this->district['name'],
            'province_id'=>$this->district->province['id'],
            'province'=>$this->district->province['name'],
             'urls' => [
                'delete_url' => route('api.phoenix.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
