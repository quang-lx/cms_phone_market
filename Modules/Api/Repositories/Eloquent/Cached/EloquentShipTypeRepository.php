<?php

namespace Modules\Api\Repositories\Eloquent\Cached;


use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Api\Transformers\ShipTypeTransformer;
use Modules\Mon\Entities\ShipType;
use Modules\Mon\Entities\ShopShipType;


class EloquentShipTypeRepository  implements ShipTypeRepository
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }


	public function getAll(Request $request) {
		$shopId = $request->get('shop_id');
		$cacheKey = CacheKey::SHIP_TYPE_ALL;
		if ($shopId) {
			$cacheKey = sprintf(CacheKey::SHIP_TYPE_SHOP, $shopId);
		}
		return  Cache::rememberForever($cacheKey, function () use ($shopId) {
			if ($shopId) {
				$subQuery = ShopShipType::query()->where('shop_id', $shopId);
				$subQuery->where('status', ShopShipType::TYPE_OFF)->select('ship_type_id');
				$data = ShipType::query()->whereNotIn('id', $subQuery);
				return ShipTypeTransformer::collection($data);
			} else {
				$data = ShipType::query()->get();
				return ShipTypeTransformer::collection($data);
			}

		});
	}

	public function findById(Request $request, $id) {
    	return ShipType::find($id);

	}
}
