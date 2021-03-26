<?php

namespace Modules\Api\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Modules\Mon\Entities\Card;
use Modules\Mon\Entities\Category;
use Modules\Mon\Entities\Game;
use Modules\Mon\Entities\Item;
use Modules\Mon\Entities\User;
use Modules\Mon\Entities\UserTurnDaily;

interface AreaRepository {
     public function getProvinces();
     public function getDistricts();
     public function getPhoenixes();
     public function getAll();
}
