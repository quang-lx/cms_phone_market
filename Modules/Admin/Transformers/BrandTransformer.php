<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class BrandTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,


             'urls' => [
                'delete_url' => route('api.brand.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
