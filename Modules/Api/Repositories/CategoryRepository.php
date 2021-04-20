<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface CategoryRepository
{
    public function listSubCat(Request $request, $catId);
    public function listByServiceType(Request $request);
}
