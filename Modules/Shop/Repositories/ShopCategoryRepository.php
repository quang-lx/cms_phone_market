<?php

namespace Modules\Shop\Repositories;

use Modules\Mon\Repositories\BaseRepository;

interface ShopCategoryRepository extends BaseRepository
{
    public function create_or_delete($data);
}
