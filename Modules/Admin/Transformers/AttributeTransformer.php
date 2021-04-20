<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class AttributeTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'code' => $this->id,
            'name' => $this->id,
            'company_id' => $this->company_id,
            'shop_id' => $this->shop_id,
             'urls' => [
                'delete_url' => route('api.attribute.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
