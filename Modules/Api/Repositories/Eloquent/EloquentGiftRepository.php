<?php

namespace Modules\Api\Repositories\Eloquent;


use Illuminate\Http\Request;
use Modules\Api\Repositories\GiftRepository;
use Modules\Mon\Entities\Gift;


class EloquentGiftRepository   implements GiftRepository {
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;

	public function __construct($model) {
		$this->model = $model;
	}


	public function serverPagingFor(Request $request) {
		$query = $this->model->newQuery();
		$query->where('status', Gift::STATUS_ACTIVE);
		$query->orderBy('created_at', 'desc');

		return $query->paginate($request->get('per_page', 10));
	}


}
