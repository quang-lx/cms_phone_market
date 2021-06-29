<?php


namespace Modules\Api\Transformers\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusHistoryTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
	        'title' => $this->title,
	        'note' => $this->note,
	        'old_status' => $this->old_status,
	        'new_status' => $this->new_status,
	        'user_id' => $this->user_id,
	        'shop_id' => $this->shop_id,
	        'order_type' => $this->order_type,
	        'created_at' => $this->created_at->format('H:i d/m/Y')
        ];


        return $data;
    }

}
