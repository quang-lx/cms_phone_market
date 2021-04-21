<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class VoucherTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,


             'urls' => [
                'delete_url' => route('api.voucher.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
