<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserGiftTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
			'gift_id' => $this->gift_id,
			'gift' =>$this->gift? new GiftTransformer($this->gift):[],
			'point' => $this->point,
			'used' => $this->used,
            'created_at'=> optional($this->created_at)->format('d/m/Y'),
        ];

        return $data;
    }

}
