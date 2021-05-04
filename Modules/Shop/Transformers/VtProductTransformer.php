<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class VtProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'price' => $this->price,
            'amount' => $this->amount,
            'vt_category_id' => $this->vt_category_id,
            'vt_category_name' => isset($this->VtCategory)?$this->VtCategory->name:'',
            'shop_id' => $this->shop_id,
            'company_id' => $this->company_id,
            'urls' => [
                'delete_url' => route('apishop.vtproduct.destroy', $this->id),
            ],

        ];


        return $data;
    }
}
