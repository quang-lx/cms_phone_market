<?php

namespace Modules\Api\Repositories\Eloquent;



use Modules\Api\Repositories\RatingRepository;


class EloquentRatingRepository extends ApiBaseRepository implements RatingRepository {
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;

	public function __construct($model) {
		$this->model = $model;
	}
	public function create($data)
	{
		return $this->model->create($data);
	}

	public function update($model, $data)
	{
		$model->update($data);
		return $model;
	}

}
