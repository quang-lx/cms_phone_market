<?php

namespace Modules\Shop\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Modules\Mon\Entities\VtProduct;

class TransferHistoryTransformer extends JsonResource
{

    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'received_at_format' => optional(Carbon::parse($this->received_at))->format('H:i d/m/Y'),
            'received_at' => $this->received_at,
            'shop_id' => $this->shop_id,
            'to_shop_id' => $this->to_shop_id,
            'shop_name' => optional($this->shop)->name,
            'company_id' => $this->company_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'updated_at_format' => optional($this->updated_at)->format('H:i d/m/Y'),
            'deleted_at' => optional($this->deleted_at)->format('d-m-Y H:i:s'),
            'created_at' => optional($this->created_at)->format('d-m-Y H:i:s'),
            'updated_at' => optional($this->updated_at)->format('d-m-Y H:i:s'),
            'status' => $this->status,
            'status_name' => $this->status_name,
            'status_color' => $this->status_color,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'type_color' => $this->type_color,
            'vtProducts' => $this->vtProducts,
             'urls' => [
                'delete_url' => route('apishop.transfer.destroy', $this->id),
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
