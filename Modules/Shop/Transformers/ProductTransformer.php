<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class ProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'status' => $this->status,
            'status_color' =>$this->status_color,
            'status_name' =>$this->status_name,
            'p_state' =>$this->p_state,
            'description' =>$this->description,
            'p_weight' =>$this->p_weight,
            's_long' =>$this->s_long,
            's_width' =>$this->s_width,
            's_height' => $this->s_height,
            'brand_id' => $this->brand_id,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'amount' => $this->amount,
            'price' => $this->price,
            'category_id' => $this->pcategories->pluck('id'),


             'urls' => [
                'delete_url' => route('api.product.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
