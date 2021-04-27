<?php

namespace Modules\Admin\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Admin\Transformers\ProductTransformer;


class VoucherTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'status_name' => $this->status_name,
            'code' => $this->code,
            'company_name' =>  optional($this->company)->name,
            'discount_type' => $this->discount_type,
            'discount_amount' => $this->discount_amount,
            'require_min_amount' => $this->require_min_amount,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
            'updated_by' =>$this->updated_by,
            'shop_id' =>$this->shop_id,
            'actived_at' => $this->actived_at,
            'expired_at' => $this->expired_at,
            'total' => $this->total,
            'total_used' => $this->total_used,
            'products' => $this->products,
            'created_name' => optional($this->creator)->name,

            'urls' => [
                'delete_url' => route('api.voucher.destroy', $this->id),
            ],

        ];

        foreach ($data['products'] as $key => $product){
            $data['products'][$key] = new ProductTransformer($product);
        }


        return $data;
    }


}
