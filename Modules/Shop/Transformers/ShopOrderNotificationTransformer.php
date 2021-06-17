<?php

namespace Modules\Shop\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;


class ShopOrderNotificationTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'title' => $this->title,
            'content' => $this->content,
            'shop_id' => $this->shop_id,
            'order_status' => $this->order_status,
            'noti_type' => $this->noti_type,
            'vt_transfer_id' => $this->vt_transfer_id,
            'order_type' => $this->order_type,
            'updated_at' =>Carbon::parse($this->updated_at)->timestamp
        ];


        return $data;
    }


}
