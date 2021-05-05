<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class TransferHistoryTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'received_at' => $this->received_at,
            'shop_id' => $this->shop_id,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,


             'urls' => [
                'delete_url' => route('apishop.transfer.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
