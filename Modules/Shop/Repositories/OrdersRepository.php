<?php

namespace Modules\Shop\Repositories;

use Modules\Mon\Repositories\BaseRepository;

interface OrdersRepository extends BaseRepository
{
    public function updateGuarantee($model, $data);
}
