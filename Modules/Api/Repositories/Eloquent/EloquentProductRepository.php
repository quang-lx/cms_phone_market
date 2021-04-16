<?php

namespace Modules\Api\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Api\Repositories\ProductRepository;
use Modules\Api\Repositories\SearchRepository;
use Modules\Mon\Entities\Pcategory;
use Modules\Mon\Entities\Product;
use Modules\Mon\Entities\Shop;

class EloquentProductRepository extends ApiBaseRepository implements ProductRepository {
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;

	public function __construct($model) {
		$this->model = $model;
	}

	public function listByCategory(Request $request, $includeSub = false) {
		$query = $this->model->query();
		if ($category_id = $request->get('category_id')) {
			$query->whereHas('pcategoryAsm', function ($q) use ($category_id) {
				$q->where('category_id', $category_id);
			});
			if ($includeSub) {

				$query->whereHas('pcategories', function ($q) use ($category_id) {
					$q->where('parent_id', $category_id);
				});
			}
		}


		if ($keyword = $request->get('q')) {
			$query->whereRaw("MATCH (name) AGAINST (?)", $this->fullTextWildcards($keyword));
		}
		return $query->active()->paginate($request->get('per_page', 10));

	}
}
