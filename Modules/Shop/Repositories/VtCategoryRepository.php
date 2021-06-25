<?php

namespace Modules\Shop\Repositories;

use Modules\Mon\Repositories\BaseRepository;

interface VtCategoryRepository extends BaseRepository
{
    public function checkExistChild($model);
    public function checkExistVtProduct($model);
}
