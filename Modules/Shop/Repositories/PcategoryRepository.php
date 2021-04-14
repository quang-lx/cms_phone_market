<?php

namespace Modules\Shop\Repositories;


use Illuminate\Http\Request;

interface PcategoryRepository
{
	public function serverPagingForTree(Request $request, $relations = null);
}
