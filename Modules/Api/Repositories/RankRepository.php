<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface RankRepository
{
	public function getAll(Request $request);
	public function rankDetail(Request $request, $rankId);
}
