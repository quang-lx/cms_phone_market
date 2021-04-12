<?php

namespace Modules\Api\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ApiShopRepository {
     public function getShopNearest($lat,$lng);

}
