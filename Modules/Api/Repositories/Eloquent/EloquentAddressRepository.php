<?php

namespace Modules\Api\Repositories\Eloquent;


use Illuminate\Http\Request;
use Modules\Api\Repositories\AddressRepository;
use Modules\Mon\Entities\User;


class EloquentAddressRepository extends ApiBaseRepository implements AddressRepository {
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;

	public function __construct($model) {
		$this->model = $model;
	}

	public function create($data) {
		$model = $this->model->create($data);
		$this->syncDefault($model);
		return $model;
	}

	public function update($model, $data) {
		$model->update($data);
		$this->syncDefault($model);
		return $model;
	}
	public function delete($model) {
		return $model->delete();
	}

	public function serverPagingFor(Request $request, User $user) {
		$query = $this->model->newQuery();
		$query->where('user_id', $user->id);

		$query->orderBy('created_at', 'desc');
		$query->orderBy('default', 'desc');


		return $query->paginate($request->get('per_page', 10));
	}

	public function findById(Request $request, $id) {
		return $this->model->find($id);
	}
	public function syncDefault($model) {
		if ($model->default) {
			$this->model->newQuery()->where('user_id', $model->user_id)->where('id', '<>', $model->id)->update(['default' => 0]);
		}
	}
}
