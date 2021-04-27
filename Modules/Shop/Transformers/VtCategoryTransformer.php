<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class VtCategoryTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'parent_id' => $this->parent_id,
            'thumbnail' => $this->thumbnail,
             'urls' => [
                'delete_url' => route('apishop.vtcategory.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
