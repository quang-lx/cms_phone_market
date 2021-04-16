<?php

namespace Modules\Shop\Repositories;


use Illuminate\Http\Request;

interface ProblemRepository
{
	public function getList(Request $request, $relations = null);
}
