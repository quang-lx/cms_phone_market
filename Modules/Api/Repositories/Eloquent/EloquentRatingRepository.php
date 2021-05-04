<?php

namespace Modules\Api\Repositories\Eloquent;


use Illuminate\Http\Request;
use Modules\Api\Repositories\RatingRepository;


class EloquentRatingRepository extends ApiBaseRepository implements RatingRepository {
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;

	public function __construct($model) {
		$this->model = $model;
	}

	public function create($data) {
		$model = $this->model->create($data);
		if (isset($data['files'])) {
			$files =  ($data['files']);
			if (!is_array($data['files'])) {
				$files  = explode(',', $files);
			}
			$model->files()->sync($files);
		}
		return $model;
	}

	public function update($model, $data) {
		$model->update($data);
		return $model;
	}

	public function listByProductId(Request $request, $product_id) {
		$query = $this->model->query();
		$query->where('product_id', $product_id);
		return $query->paginate($request->get('per_page', 10));

	}

}
