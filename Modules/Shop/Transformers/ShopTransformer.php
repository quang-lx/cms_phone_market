<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class ShopTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'status' => $this->status,
            'status_name' =>$this->status_name,
            'status_color' =>$this->status_color,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,


             'urls' => [
                'delete_url' => route('api.shop.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
