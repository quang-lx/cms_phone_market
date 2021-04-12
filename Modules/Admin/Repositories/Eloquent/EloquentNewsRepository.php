<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Events\News\NewsWasCreated;
use Modules\Admin\Events\News\NewsWasUpdated;
use Modules\Admin\Repositories\NewsRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentNewsRepository extends BaseRepository implements NewsRepository
{
    public function create($data)
    {
        $model = $this->model->create($data);
        event(new NewsWasCreated($model, $data));
        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new NewsWasUpdated($model, $data));
        return $model;
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('id', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('creator', function ($query) use ($keyword) {
                        $query->where('username', 'like', "%$keyword%");
                    });

            });
        }

        if ($status = $request->get('status')) {
             $query->where('status', $status);
        }
        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        if ($request->get('group_by') !== null) {
            $query->groupBy(explode(",", $request->get('group_by')));
        }
        return $query->paginate($request->get('per_page', 10));
    }
}
