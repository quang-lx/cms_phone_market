<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Api\Repositories\CategoryRepository;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Repositories\SearchRepository;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Shop;

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
}
