<?php

namespace Modules\Api\Repositories\Eloquent;

use Modules\Api\Repositories\HomeSettingRepository;
use Modules\Api\Transformers\Home\HomeBannerTransformer;
use Modules\Api\Transformers\Home\HomePCategoryTransformer;
use Modules\Api\Transformers\Home\HomeProductTransformer;
use Modules\Mon\Entities\Banners;
use Modules\Mon\Entities\HomeSetting;
use Modules\Mon\Entities\Pcategory;
use Modules\Mon\Entities\Product;

class EloquentHomeSettingRepository implements HomeSettingRepository {

	public function get() {
		$homesettings = HomeSetting::query()->orderBy('order_')->get();
		$data = [];
		foreach ($homesettings as $homesetting) {
			$listId = explode(',', $homesetting->content);
			$item = [
				'type' => $homesetting->type,
				'title' => $homesetting->title
			];
			switch ($homesetting->type) {
				case HomeSetting::TYPE_BANNER:
					$banners = Banners::query()->whereIn('id', $listId)->get();

					$item['data'] = HomeBannerTransformer::collection($banners);
					break;
				case HomeSetting::TYPE_BEST_SELL:
				case HomeSetting::TYPE_BUY_NOW:
				case HomeSetting::TYPE_SUGGEST:
					$products = Product::query()->whereIn('id', $listId)->get();
					$item['data'] = HomeProductTransformer::collection($products);
					break;

				case HomeSetting::TYPE_CATEGORY:
				case HomeSetting::TYPE_SERVICE:
					$categories = Pcategory::query()->whereIn('id', $listId)->get();
					$item['data'] = HomePCategoryTransformer::collection($categories);
					break;
			}
			$data[] = $item;
		}
		return $data;
	}
}
