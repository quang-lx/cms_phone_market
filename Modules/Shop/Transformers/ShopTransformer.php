<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class ShopTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
//            'name' => $this->name,
//            'district_id' => $this->district_id,
//            'code' => $this->code,
//            'type' => $this->type,
//            'district'=>$this->district['name'],
//            'province_id'=>$this->district->province['id'],
//            'province'=>$this->district->province['name'],

             'urls' => [
                'delete_url' => route('api.shop.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
