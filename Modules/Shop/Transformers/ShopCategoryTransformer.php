<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Facades\Auth;

class ShopCategoryTransformer extends JsonResource
{


    public function toArray($request)
    {
        $status_shop_category = $this->shopCategory()->where('shop_id',Auth::user()->shop_id)->first();
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'status_shop_category' => isset($status_shop_category)?1:0,

        ];


        return $data;
    }


}
