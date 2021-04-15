<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Events\Category\CategoryWasCreated;
use Modules\Admin\Events\Category\CategoryWasUpdated;
use Modules\Admin\Repositories\CategoryRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Shop\Repositories\PcategoryRepository;
use Modules\Shop\Repositories\ProblemRepository;

class EloquentProblemRepository extends BaseRepository implements ProblemRepository
{


	public function getList(Request $request, $relations = null) {
		$query = $this->newQueryBuilder();
		if ($relations) {
			$query = $query->with($relations);
		}
		return $query->select(['id', 'title'])->get();
	}
}
