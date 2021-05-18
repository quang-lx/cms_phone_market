<?php

namespace Modules\Api\Repositories\Eloquent\Cached;


use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Repositories\PaymentMethodRepository;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Api\Transformers\PaymentMethodTransformer;
use Modules\Api\Transformers\ShipTypeTransformer;
use Modules\Mon\Entities\PaymentMethod;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\ShopShipType;


class EloquentPaymentMethodRepository  implements PaymentMethodRepository
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }


	public function getAll(Request $request) {
		$shopId = $request->get('shop_id');
		$cacheKey = CacheKey::PAYMENT_METHOD_ALL;
		if ($shopId) {
			$cacheKey = sprintf(CacheKey::PAYMENT_METHOD_SHOP, $shopId);
		}
		return  Cache::rememberForever($cacheKey, function () use ($shopId) {
			$data = PaymentMethod::query()->get();
			return PaymentMethodTransformer::collection($data);

		});
	}

	public function findById(Request $request, $id) {
    	return $this->model->find($id);

	}
}
