<?php


namespace Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Modules\Mon\Entities\Cart;
use Modules\Mon\Entities\Orders;
use Modules\Mon\Entities\Rank;

class UserTransformer extends JsonResource
{


    public function toArray($request)
    {
        $defaultAddress = $this->defaultAddress($this->addresses);
        $data = [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
	        'birthday' => $this->birthday,
	        'rank' => $this->rank_id ? Rank::rankById($this->rank_id): null,
	        'rank_point' => $this->rank_point,
	        'phone' => $this->phone,
	        'gender' => $this->gender,
	        'email' => $this->email,
            'total_order' => $this->orderCount($this->id),
            'cart_products' => $this->cartProduct($this->id),
	        'avatar' => $this->avatar? new MediaShortTransformer($this->avatar): null,
            'default_address' => $defaultAddress? new AddressTransformer($defaultAddress): null,
            'noti_not_seen' => $this->orderNotificationNotSeen->count(),
			'providers' => $this->connectedAccount? ConnectedAccountTransformer::collection($this->connectedAccount): null

        ];


        return $data;
    }

    /**
     * @param $addresses Collection
     */
    public function defaultAddress($addresses) {
        return $addresses->firstWhere('default', 1);
    }
    public function orderCount($userId) {
    	return Orders::query()->where('user_id', $userId)->count();
    }
	public function cartProduct($userId) {
		$cart =  Cart::query()->where('user_id', $userId)->first();
		if (!$cart) return 0;
		return $cart->cartProducts()->count();
	}
}
