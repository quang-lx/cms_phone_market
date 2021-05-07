<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface RatingShopRepository
{
	public function create($data);
	public function update($model, $data);
	public function listByShopId(Request $request, $shop_id);
}
