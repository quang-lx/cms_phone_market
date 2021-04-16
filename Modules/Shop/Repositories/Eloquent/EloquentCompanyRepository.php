<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\CompanyRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Shop\Events\Company\CompanyWasUpdated;
class EloquentCompanyRepository extends BaseRepository implements CompanyRepository
{
    public function update($model, $data)
    {
        $model->update($data);
        event(new CompanyWasUpdated($model, $data));
        return $model;
    }
}
