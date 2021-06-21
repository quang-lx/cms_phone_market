<?php


namespace Modules\Api\Transformers\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentHistoryTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'payment_method_id' => $this->payment_method_id,
            'pay_method_name' => $this->pay_method_name,
	        'pay_amount' =>  $this->pay_amount
        ];


        return $data;
    }

}
