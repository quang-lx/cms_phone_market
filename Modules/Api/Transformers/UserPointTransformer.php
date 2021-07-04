<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPointTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'title' => $this->title,
            'point' => $this->point,
            'point_display' => $this->point> 0? '+'.$this->point: $this->point,
            'created_at'=> optional($this->created_at)->format('d/m/Y'),
        ];

        return $data;
    }

}
