<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class VtShopProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'vt_product_name' => $this->vtProudct->name,
            'vt_product_id' => $this->vt_product_id,
            'shop_name' => $this->shop->name,
            'company_id' => $this->company_id,
            'amount' => $this->amount,
            'created_by' => $this->user->name,
            'updated_by' => $this->updated_by,

             'urls' => [
                'delete_url' => route('apishop.vtshopproduct.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
