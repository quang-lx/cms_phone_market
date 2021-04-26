<?php

namespace Modules\Api\Repositories;


use Illuminate\Http\Request;

interface ProductRepository
{
    public function listByCategory(Request $request, $includeSub = false);
    public function listByService(Request $request);
    public function detail($id);
}
