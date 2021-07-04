<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface GiftRepository
{
	public function serverPagingFor(Request $request);
}
