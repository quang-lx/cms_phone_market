<?php

namespace Modules\Api\Repositories\Eloquent;

use App\Models\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Repositories\CategoryRepository;
use Modules\Mon\Entities\Brand;
use Modules\Mon\Entities\Pcategory;
use Modules\Mon\Entities\Problem;

class EloquentCategoryRepository implements CategoryRepository
{
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;

	public function __construct($model)
	{
		$this->model = $model;
	}

	public function listSubCat(Request $request, $catId) {
		return $this->model->query()->where('parent_id', $catId)->get();
	}
	public function listByServiceType(Request $request) {
		return $this->model->query()->where('type', Pcategory::TYPE_SERVICE)->get();
	}

	public function listProblemByCat(Request $request, $catId) {
		$cacheKey = sprintf(CacheKey::CATEGORY_PROBLEM, $catId);

		$problems = Cache::tags([CacheKey::TAG_CATEGORY_PROBLEM])->rememberForever($cacheKey, function () use ($catId) {
			return Problem::query()->whereHas('problemPcategory', function ($q) use ($catId) {
				$q->where('pcategory_id', $catId);
			})->get();
		});

		return $problems;
	}

	public function listBrandByCat(Request $request, $catId) {
		$cacheKey = sprintf(CacheKey::CATEGORY_BRAND, $catId);

		$brands = Cache::tags([CacheKey::TAG_CATEGORY_BRAND])->rememberForever($cacheKey, function () use ($catId) {
			return Brand::query()->whereHas('BrandPcategory', function ($q) use ($catId) {
				$q->where('pcategory_id', $catId);
			})->get();
		});


		return $brands;
	}
}
