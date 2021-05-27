<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'type' => $this->type,
            'title' => $this->title,
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'discount_amount' => $this->discount_amount,
            'require_min_amount' => $this->require_min_amount,

        ];


        return $data;
    }

}
