<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface UserGiftRepository
{
	public function create($user, $gift);
	public function serverPagingFor(Request $request);
}
