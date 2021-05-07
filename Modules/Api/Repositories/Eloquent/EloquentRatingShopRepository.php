<?php

namespace Modules\Api\Repositories\Eloquent;


use Illuminate\Http\Request;
use Modules\Api\Repositories\RatingRepository;
use Modules\Api\Repositories\RatingShopRepository;


class EloquentRatingShopRepository extends ApiBaseRepository implements RatingShopRepository {
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

	public function listByShopId(Request $request, $shop_id) {
		$query = $this->model->query();
		$query->where('shop_id', $shop_id);
		if($rating = $request->get('rating')) {
			if ($rating == 'media') {
				$query->whereHas('files');
			}else {
				$query->where('rating', $rating);
			}
		}

		return $query->paginate($request->get('per_page', 10));

	}

}
