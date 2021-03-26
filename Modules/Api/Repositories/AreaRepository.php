<?php

namespace Modules\Api\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface AreaRepository {
     public function getProvinces();
     public function getDistricts(Request $request);
     public function getPhoenixes(Request $request);
     public function getAll(Request $request);
}
