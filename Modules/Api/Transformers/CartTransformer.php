<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CartTransformer extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
	        'shops' => $this->groupProductByShop($this->cartProducts)

        ];


        return $data;
    }

	public function groupProductByShop($cartProducts) {
    	$shops = [];
    	foreach ($cartProducts as $cartProduct) {
    		if (isset($shops[$cartProduct->shop_id])) {
    			$shops[$cartProduct->shop_id]['products'][] = new CartProductTransformer($cartProduct);
		    } else {
			    $shops[$cartProduct->shop_id] = [];
			    $shops[$cartProduct->shop_id]['shop_id']= $cartProduct->shop_id;
			    $shops[$cartProduct->shop_id]['shop_name']= $cartProduct->shop_name;
			    $shops[$cartProduct->shop_id]['products'][] = new CartProductTransformer($cartProduct);

		    }
	    }
    	return $shops;
	}
}
