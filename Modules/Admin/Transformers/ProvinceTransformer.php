<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class ProvinceTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'type'=> $this->type,
            'phone_code' => $this->phone_code,

            'urls' => [
                'delete_url' => route('api.province.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
