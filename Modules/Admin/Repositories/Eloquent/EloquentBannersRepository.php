<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\BannersRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Modules\Admin\Events\Banners\BannersWasCreated;
use Modules\Admin\Events\Banners\BannersWasUpdated;

class EloquentBannersRepository extends BaseRepository implements BannersRepository
{
    public function create($data)
    {
        $model = $this->model->create($data);
        event(new BannersWasCreated($model, $data));
        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new BannersWasUpdated($model, $data));
        return $model;
    }
}
