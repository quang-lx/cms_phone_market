<?php

namespace Modules\Api\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Repositories\AreaRepository;


class OrderUserNotiTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'order_id' => $this->order_id,
            'seen' => $this->seen,
            'noti_type' => $this->noti_type,
	        'created_at'=> optional($this->created_at)->format('d/m/Y H:i'),
	        'product_thumbnail' => ($this->order && $this->order->thumbnail) ? new MediaShortTransformer($this->order->thumbnail): null,

        ];


        return $data;
    }




}
