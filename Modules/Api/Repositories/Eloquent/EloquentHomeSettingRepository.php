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

class EloquentHomeSettingRepository implements HomeSettingRepository
{

	public function get() {
		$homesettings = HomeSetting::query()->orderBy('order_')->get();
		$data = [];
		foreach ($homesettings as $homesetting) {
			$listId = explode(',',$homesetting->content);
			switch ($homesetting->type) {
				case HomeSetting::TYPE_BANNER:
					$banners = Banners::query()->whereIn('id', $listId)->get();
					$data[] = HomeBannerTransformer::collection($banners);
					break;
				case HomeSetting::TYPE_BEST_SELL:
				case HomeSetting::TYPE_BUY_NOW:
					$products = Product::query()->whereIn('id', $listId)->get();
					$data[] = HomeProductTransformer::collection($products);
					break;

				case HomeSetting::TYPE_CATEGORY:
					$categories = Pcategory::query()->whereIn('id', $listId)->get();
					$data[] = HomePCategoryTransformer::collection($categories);
					break;

			}
		}
		return $data;
	}
}
