<?php

namespace Modules\Shop\Transformers;

use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Orders;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Mon\Entities\ProductAttributeValue;

class OrdersBuySellTransformer extends JsonResource
{


    public function toArray($request)
    {
        $list_product = $this->orderBuySellProduts()->get();
        foreach ($list_product as $key => $value) {
            $value->thumbnail = $value->product->thumbnail;
            $value->name = $value->product->name;
            $value->price_product = $value->price_sale;
            $value->price_amount = $value->price_sale*$value->quantity;
            $value->attr_value = optional(optional($value->productValue)->attributeValues)->name;
        }
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
            'status_value' => $this->status,
            'payment_status' => $this->payment_status,
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'ship_fee' => $this->ship_fee,
            'pay_price' => $this->pay_price,
            'list_product' => $list_product->toArray(),
    
            'ship_address_id' => $this->ship_address_id,
            'ship_province_id' => $this->ship_province_id,
            'ship_province_name' => $this->ship_province_name,
            'ship_district_id' => $this->ship_district_id,
            'ship_district_name' => $this->ship_district_name,
            'ship_phoenix_id' => $this->ship_phoenix_id,
            'ship_phoenix_name' => $this->ship_phoenix_name,
            'ship_address' => $this->ship_address,
            'created_at' => $this->created_at->format('d-m-Y'),
            'shop_done' => $this->shop_done,
            'order_status_history' =>$this->orderStatusHistory($this->orderStatusHistory),
            'pay_method_name' => optional($this->paymentHistory)->pay_method_name,

            'shop_voucher_id' => $this->shop_voucher_id,
            'shop_voucher_code' => $this->shop_voucher_code,
            'shop_discount'=> $this->shop_discount,
            'sys_voucher_id'=> $this->sys_voucher_id,
            'sys_voucher_code'=> $this->sys_voucher_code,

             'urls' => [
                'delete_url' => route('apishop.orders.destroy', $this->id),
            ],

        ];


        return $data;
    }

    public function orderStatusHistory($order_status)
    {
       $result = [];
       foreach ($order_status as $value) {
           $data = [
                'title' => $value['title'],
                'date' => $value['created_at']->format('H:i d/m/Y'),
           ];
           array_push($result,$data);
       }
       return $result;
    }

    


}
