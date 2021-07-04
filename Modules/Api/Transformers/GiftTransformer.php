<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Modules\Mon\Entities\Voucher;

class GiftTransformer extends JsonResource
{


    public function toArray($request)
    {
//        $listProduct = null;
//        if ($this->type == Voucher::TYPE_DISCOUNT_PRODUCT) {
//            $listProduct = $this->listProductId($this->id);
//        }

        $data = [
			'id' => $this->id ,
			'name' => $this->name,
			'point' => $this->point,
			'amount' => $this->amount,
			'used_amount' => $this->used_amount,
			'status' => $this->status,
			'description' => $this->description,
            'created_at'=> optional($this->created_at)->format('d/m/Y') ,
            'thumbnail' => $this->thumbnail?  new MediaShortTransformer($this->thumbnail): null


        ];


        return $data;
    }


}
