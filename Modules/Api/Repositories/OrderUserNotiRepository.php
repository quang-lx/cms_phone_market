<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface OrderUserNotiRepository
{
	public function getList(Request $request);
}
