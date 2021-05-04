<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface RatingRepository
{
	public function create($data);
	public function update($model, $data);
	public function listByProductId(Request $request, $product_id);
}
