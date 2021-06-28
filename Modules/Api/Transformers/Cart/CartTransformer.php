<?php


namespace Modules\Api\Transformers\Cart;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Api\Transformers\MediaShortTransformer;
use Modules\Mon\Entities\Shop;

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
		    $shopIndex = -1;
    		foreach ($shops as $key => $shop) {
    			if ($shop['shop_id'] == $cartProduct->shop_id) {
				    $shopIndex = $key;
			    }
		    }
    		if ($shopIndex >= 0) {
			     $shops[$shopIndex]['products'][] = new CartProductTransformer($cartProduct);
		    } else {
			    $shops[$cartProduct->shop_id] = [];
			    $shopRow = [];
			    $shopRow['shop_id']= $cartProduct->shop_id;
			    $shopRow['shop_name']= $cartProduct->shop_name;
			    $shopRow['products'][] = new CartProductTransformer($cartProduct);
				$shop = Shop::find($cartProduct->shop_id);
				if ($shop) {
					$shopRow['thumbnail']= $shop->thumbnail?  new MediaShortTransformer($shop->thumbnail): null;

				}
			    $shops[] = $shopRow;
		    }
	    }
    	return $shops;
	}
}
