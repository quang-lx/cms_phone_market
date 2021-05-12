<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class OrdersTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,


             'urls' => [
                'delete_url' => route('api.orders.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
