<?php

namespace Modules\Shop\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Mon\Entities\Orders;

class OrdersTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->username,
            'phone' => $this->user->phone,
            'user' => $this->user,
            'company_id' => $this->company_id,
            'shop' => $this->shop,
            'ship_type_id' => $this->ship_type_id,
            'order_type' => $this->order_type,
            'fix_time' => $this->fix_time,
            'status' => $this->status_name,
            'status_value' => $this->status,
            'payment_status' => $this->payment_status,
            'total_price' => $this->total_price,
            'discount' => $this->discount,
            'ship_fee' => $this->ship_fee,
            'pay_price' => $this->pay_price,
            'type_other' => $this->type_other,
            'product_name' => $this->orderProducts->product_title,
            'product_note' => $this->orderProducts->note,

            'ship_address_id' => $this->ship_address_id,
            'ship_province_id' => $this->ship_province_id,
            'ship_province_name' => $this->ship_province_name,
            'ship_district_id' => $this->ship_district_id,
            'ship_district_name' => $this->ship_district_name,
            'ship_phoenix_id' => $this->ship_phoenix_id,
            'ship_phoenix_name' => $this->ship_phoenix_name,
            'ship_address' => $this->ship_address,
            'created_at' => $this->created_at->format('H:i d/m/Y'),
            'shop_done' => $this->shop_done,
            'fix_time_date' => $this->fix_time_date,
            'pay_method_name' => optional($this->paymentHistory)->pay_method_name,
            'order_status_history' =>$this->orderStatusHistory($this->orderStatusHistory),

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
                'status' => $this->getStatusNameAttribute($value['old_status']),
                'date' => $value['created_at']->format('H:i d/m/Y'),
           ];
           array_push($result,$data);
       }
       return $result;
    }
    public function getStatusNameAttribute($status)
	{
		$statusName = '';
		switch ($status) {
			case Orders::STATUS_ORDER_CREATED:
				$statusName = 'Gửi yêu cầu';
				break;
			case Orders::STATUS_ORDER_CONFIRMED:
				$statusName = 'Xác nhận,sửa chữa';
				break;
			case Orders::STATUS_ORDER_WAIT_CLIENT_CONFIRM:
				$statusName = 'Người dùng xác nhận';
				break;
			case Orders::STATUS_ORDER_FIXING:
				$statusName = 'Gửi tới shop';
				break;
            case Orders::STATUS_ORDER_WARRANTING:
                $statusName = 'Gửi tới shop';
                break;
			case Orders::STATUS_ORDER_SENDING:
				$statusName = 'Shop gửi lại';
				break;
            case Orders::STATUS_ORDER_DONE:
                $statusName = 'Nhận lại sản phẩm';
                break;
		}
		return $statusName;
	}


}
