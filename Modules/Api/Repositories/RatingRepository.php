<?php

namespace Modules\Api\Repositories;


interface RatingRepository
{
	public function create($data);
	public function update($model, $data);
}
