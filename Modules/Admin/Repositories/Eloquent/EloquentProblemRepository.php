<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\ProblemRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentProblemRepository extends BaseRepository implements ProblemRepository
{
	public function create($data)
	{

		$model =  $this->model->create($data);
		$model->pcategories()->sync($data['category_id']);
	}

	public function update($model, $data)
	{
		$model->update($data);
		$model->pcategories()->sync($data['category_id']);
	}
}
