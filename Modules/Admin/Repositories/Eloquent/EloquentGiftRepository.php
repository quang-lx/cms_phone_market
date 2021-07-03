<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Events\Gift\GiftWasCreated;
use Modules\Admin\Events\Gift\GiftWasUpdated;
use Modules\Admin\Repositories\GiftRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentGiftRepository extends BaseRepository implements GiftRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->orWhere('name','LIKE',"%{$search}%");
                $q->orWhere('point',$search);
            });
  
        }

        if ($request->get('searchDate') !== null) {
            $searchDate = $request->get('searchDate');
            $query->where(function ($c) use ($searchDate) {
                $c->where('created_at', '>=', $searchDate[0]);
                $c->where('created_at', '<=', $searchDate[1]);
            });

        }

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where(function ($c) use ($status) {
                $c->where('status', '=', $status);
            });

        }


        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }
    public function create($data)
    {
        $model = $this->model->create($data);
        event(new GiftWasCreated($model, $data));
        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new GiftWasUpdated($model, $data));
        return $model;
    }
}
