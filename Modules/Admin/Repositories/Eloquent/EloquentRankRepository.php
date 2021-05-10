<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\RankRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Admin\Events\Rank\RankWasCreated;
use Modules\Admin\Events\Rank\RankWasUpdated;
use Illuminate\Http\Request;

class EloquentRankRepository extends BaseRepository implements RankRepository
{
    public function create($data)
    {
        $model = $this->model->create($data);
        event(new RankWasCreated($model, $data));
        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new RankWasUpdated($model, $data));
        return $model;
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('created_at', 'asc');
        }

        if ($request->get('type') !== null) {
            $type = $request->get('type');
            $query->where(function ($q) use ($type) {
                $q->orWhere('type',$type);
            });
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name','LIKE', "%{$keyword}%");
            });
        }
        return $query->paginate($request->get('per_page', 10));    
    }
}
