<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\FbNotificationRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;

class EloquentFbNotificationRepository extends BaseRepository implements FbNotificationRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('topic') !== null) {
            $topic = $request->get('topic');
            $query->where(function ($q) use ($topic) {
                $q->orWhere('topic',$topic);
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }
}
