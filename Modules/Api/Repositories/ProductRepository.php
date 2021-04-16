<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface ProductRepository
{
    public function listByCategory(Request $request);
}
