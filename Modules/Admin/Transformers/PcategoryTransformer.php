<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class PcategoryTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'parent_id' => $this->parent_id,

             'urls' => [
                'delete_url' => route('api.pcategory.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
