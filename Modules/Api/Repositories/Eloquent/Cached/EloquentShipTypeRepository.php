<?php

namespace Modules\Api\Repositories\Eloquent\Cached;


use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Repositories\ShipTypeRepository;
use Modules\Api\Transformers\ShipTypeTransformer;
use Modules\Mon\Entities\ShipType;


class EloquentShipTypeRepository  implements ShipTypeRepository
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }


	public function getAll(Request $request) {

		return  Cache::rememberForever(CacheKey::SHIP_TYPE_ALL, function () use ($request) {
			$data = ShipType::query()->get();
			return ShipTypeTransformer::collection($data);
		});
	}
}
