<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Api\Repositories\ApiRepository;
use Illuminate\Http\Request;
use Modules\Api\Repositories\ApiShopRepository;
use Modules\Mon\Entities\Category;
use Modules\Mon\Entities\Game;
use Modules\Mon\Entities\Item;
use Modules\Mon\Entities\Mission;
use Modules\Mon\Entities\News;
use Modules\Mon\Entities\Shop;
use Modules\Mon\Entities\UserMissionDaily;

class EloquentApiShopRepository implements ApiShopRepository
{

	public function getShopNearest($lat, $lng) {
		$result = new Collection();
		Shop::query()->whereNotNull(['lat','lng'])->chunk(100, function ($shops) use ($lat, $lng, $result) {
				foreach ($shops as $shop) {
					if (map_distance_km($shop->lat, $shop->lng, $lat, $lng) <= env('SHOP_NEAREST_KM', 5)) {
						$result->push($shop);
					}
					if ($result->count() == 20) return false;
				}
		});
		return $result;
	}
}
