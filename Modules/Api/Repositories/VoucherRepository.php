<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface VoucherRepository
{
	public function getListVoucher(Request $request);
}
