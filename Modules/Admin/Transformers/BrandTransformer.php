<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class BrandTransformer extends JsonResource
{


    public function toArray($request)
    {
        $category=[];
        $category_id=$this->BrandPcategory->pluck('pcategory_id')->toArray();
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'status_color' => $this->status_color,
            'category' => $category,
            'category_id' => $category_id,
            'type_name' => $this->type_name,
             'urls' => [
                'delete_url' => route('api.brand.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
