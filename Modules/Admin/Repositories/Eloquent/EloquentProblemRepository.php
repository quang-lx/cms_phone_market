<?php

namespace Modules\Admin\Repositories\Eloquent;

use App\Models\CacheKey;
use Illuminate\Support\Facades\Cache;
use Modules\Admin\Repositories\ProblemRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentProblemRepository extends BaseRepository implements ProblemRepository
{
	public function create($data)
	{

		$model =  $this->model->create($data);
		$model->pcategories()->sync($data['category_id']);
		Cache::tags([CacheKey::TAG_CATEGORY_PROBLEM])->flush();
		return $model;
	}

	public function update($model, $data)
	{
		$model->update($data);
		$model->pcategories()->sync($data['category_id']);
		Cache::tags([CacheKey::TAG_CATEGORY_PROBLEM])->flush();
		return $model;
	}

	public function destroy($model)
	{
		Cache::tags([CacheKey::TAG_CATEGORY_PROBLEM])->flush();
		return $model->delete();
	}

}
