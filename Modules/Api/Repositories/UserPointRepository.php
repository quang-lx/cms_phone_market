<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface UserPointRepository
{
	public function serverPagingFor(Request $request);
}
