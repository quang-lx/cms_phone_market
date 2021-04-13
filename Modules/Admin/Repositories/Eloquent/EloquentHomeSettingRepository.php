<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\HomeSettingRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentHomeSettingRepository extends BaseRepository implements HomeSettingRepository
{
	public function serverPagingFor(Request $request, $relations = null)
	{
		$query = $this->newQueryBuilder();
		if ($relations) {
			$query = $query->with($relations);
		}

		$query->orderBy('order_', 'asc');
		return $query->paginate($request->get('per_page', 100));
	}
}
