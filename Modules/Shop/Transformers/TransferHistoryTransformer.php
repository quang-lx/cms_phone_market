<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TransferHistoryTransformer extends JsonResource
{

    public function toArray($request)
    {
        //convert string to carbon
        $receivedAt = optional(Carbon::parse($this->received_at));
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'received_at' => $receivedAt->format('d-m-Y H:i:s'),
            'shop_id' => $this->shop_id,
            'shop_name' => optional($this->shop)->name,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => optional($this->deleted_at)->format('d-m-Y H:i:s'),
            'created_at' => optional($this->created_at)->format('d-m-Y H:i:s'),
            'updated_at' => optional($this->updated_at)->format('d-m-Y H:i:s'),
            'status' => $this->status,
            'status_name' => $this->status_name,
            'status_color' => $this->status_color,
            'products' => $this->products,


             'urls' => [
                'delete_url' => route('apishop.transfer.destroy', $this->id),
            ],

        ];
        
        foreach ($data['products'] as $key => $product){
            $data['products'][$key]['count'] = $product->pivot->amount;
        }

        return $data;
    }


}
