<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Facades\Auth;

class ShopCategoryTransformer extends JsonResource
{


    public function toArray($request)
    {
        $status_shop_ship_type = $this->ShopShipType()->where('shop_id',Auth::user()->shop_id)->first();

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'status_shop_ship_type' => isset($status_shop_ship_type)?$status_shop_ship_type['status']:1,

             'urls' => [
                'delete_url' => route('api.shopcategory.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
