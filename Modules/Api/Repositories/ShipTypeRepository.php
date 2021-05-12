<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface ShipTypeRepository
{
	public function getAll(Request $request);
	public function findById(Request $request, $id);
}
