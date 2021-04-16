<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface ProductRepository
{
    public function listBuyCategory(Request $request);
}
