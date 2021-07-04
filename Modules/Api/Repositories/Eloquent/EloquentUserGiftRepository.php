<?php

namespace Modules\Api\Repositories\Eloquent;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Api\Repositories\AddressRepository;
use Modules\Api\Repositories\UserGiftRepository;
use Modules\Api\Repositories\UserPointRepository;
use Modules\Mon\Entities\User;


class EloquentUserGiftRepository   implements UserGiftRepository {
	/** @var \Illuminate\Database\Eloquent\Model */
	protected $model;

	public function __construct($model) {
		$this->model = $model;
	}

	public function create($user, $gift) {
		try {
			DB::beginTransaction();
			$this->model->create([
				'user_id' => $user->id,
				'gift_id' => $gift->id,
				'point' => $gift->point,
				'used' => 0,
			]);
			$user->rank_point -= $gift->point;
			$user->save();
			DB::commit();
			return true;
		} catch (\Exception $exception) {
			DB::rollBack();
			Log::error($exception->getMessage());
			return false;
		}


	}

	public function serverPagingFor(Request $request) {
		$user = Auth::user();
		$query = $this->model->newQuery();
		$query->where('user_id', $user->id);

		$query->orderBy('created_at', 'desc');

		return $query->paginate($request->get('per_page', 10));
	}


}
