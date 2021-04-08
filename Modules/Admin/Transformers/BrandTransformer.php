<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class BrandTransformer extends JsonResource
{


    public function toArray($request)
    {
        $category=[];
        $brand_pcategory = $this->BrandPcategory;
        foreach ($brand_pcategory as $key => $value) {
           array_push($category,['id'=>$value->Pcategory->id,'name'=>$value->Pcategory->name]);
        }
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'category' => $category,
             'urls' => [
                'delete_url' => route('api.brand.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
