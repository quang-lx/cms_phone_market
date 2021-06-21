<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class StorageProductTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'received_at' => $this->received_at,
            'received_at_format' => optional(Carbon::parse($this->received_at))->format('H:i d/m/Y'),
            'shop_id' => $this->shop_id,
            'to_shop_id' => $this->to_shop_id,
            'shop_name' => optional($this->shop)->name,
            'to_shop_name' => optional($this->toshop)->name,
            'company_id' => $this->company_id,
            'company_name' => optional($this->company)->name,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => optional($this->deleted_at)->format('H:i d/m/Y'),
            'created_at' => optional($this->created_at)->format('H:i d/m/Y'),
            'updated_at' => optional($this->updated_at)->format('H:i d/m/Y'),
            'status' => $this->status,
            'status_name' => $this->status_name,
            'status_color' => $this->status_color,
            'vtProducts' => $this->vtProducts,
            'note' => $this->note,
            'type' => $this->type,


             'urls' => [
                'delete_url' => route('apishop.storageproduct.destroy', $this->id),
            ],

        ];

        foreach ($data['vtProducts'] as $key => $product){
            $data['vtProducts'][$key]['count'] = $product->pivot->amount;
            $data['vtProducts'][$key]['catId'] = $product->vt_category_id;
            $listVtProduct = array();
            $listVtProduct[0] = array(
                'id' => $data['vtProducts'][$key]->id,
                'name' => $data['vtProducts'][$key]->name
            );
            $data['vtProducts'][$key]['listVtProduct'] = $listVtProduct;
        }


        return $data;
    }


}
