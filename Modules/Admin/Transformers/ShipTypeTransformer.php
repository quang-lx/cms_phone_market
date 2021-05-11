<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class ShipTypeTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            
             'urls' => [
                'delete_url' => route('api.shiptype.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
