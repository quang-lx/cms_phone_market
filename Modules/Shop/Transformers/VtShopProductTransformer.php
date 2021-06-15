<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class VtShopProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'vt_product_name' => optional($this->vtProudct)->name,
            'vt_product_id' => $this->vt_product_id,
            'shop_name' => optional($this->shop)->name,
            'company_id' => $this->company_id,
            'amount' => $this->amount,
            'created_by' => optional($this->user)->name,
            'updated_by' => $this->updated_by,
            'created_at' =>$this->created_at->format('H:i d/m/Y')

        ];


        return $data;
    }


}
