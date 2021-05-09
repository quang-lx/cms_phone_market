<?php

namespace Modules\Api\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ApiShopRepository {
     public function getShopNearest($lat,$lng);
     public function getShopBaoHanh(Request $request, $user);
     public function getList(Request $request);
     public function detail(Request $request, $id);

}
