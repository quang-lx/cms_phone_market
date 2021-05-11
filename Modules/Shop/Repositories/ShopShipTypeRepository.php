<?php

namespace Modules\Shop\Repositories;

use Modules\Mon\Repositories\BaseRepository;

interface ShopShipTypeRepository extends BaseRepository
{
    public function create_or_update($data);
}
