<?php

namespace Modules\Api\Repositories\Eloquent;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\UserPointRepository;
use Modules\Mon\Entities\User;


class EloquentUserPointRepository   implements UserPointRepository {
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;

	public function __construct($model) {
		$this->model = $model;
	}


	public function serverPagingFor(Request $request) {
		$user = Auth::user();
		$query = $this->model->newQuery();
		$query->where('user_id', $user->id);

		$query->orderBy('created_at', 'desc');

		return $query->paginate($request->get('per_page', 10));
	}


}
