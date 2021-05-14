<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;


class OrdersTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'user' => $this->user,
            'company_id' => $this->company_id,
            'shop' => $this->shop,
            'ship_type_id' => $this->ship_type_id,
            'order_type' => $this->order_type,
            'status' => $this->status_name,
            'payment_status' => $this->payment_status,
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'ship_fee' => $this->ship_fee,
            'pay_price' => $this->pay_price,
            'product_name' => $this->orderProducts->product->name,
    
            'ship_address_id' => $this->ship_address_id,
            'ship_province_id' => $this->ship_province_id,
            'ship_province_name' => $this->ship_province_name,
            'ship_district_id' => $this->ship_district_id,
            'ship_district_name' => $this->ship_district_name,
            'ship_phoenix_id' => $this->ship_phoenix_id,
            'ship_phoenix_name' => $this->ship_phoenix_name,
            'ship_address' => $this->ship_address,
            'created_at' => $this->created_at->format('d-m-Y'),

             'urls' => [
                'delete_url' => route('apishop.orders.destroy', $this->id),
            ],

        ];


        return $data;
    }


}
