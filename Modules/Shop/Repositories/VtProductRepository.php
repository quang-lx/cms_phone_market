<?php

namespace Modules\Shop\Repositories;

use Modules\Mon\Repositories\BaseRepository;

interface VtProductRepository extends BaseRepository
{
    public function import($import_excel_id);
    public function checkAmountVtProduct($model);

}
