<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\AccountRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
class EloquentAccountRepository extends BaseRepository implements AccountRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        $query->where('type',2);
        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('username', 'LIKE', "%{$keyword}%");
                $q->orWhere('phone', 'LIKE', "%{$keyword}%");
                $q->orWhere('email', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->get('rank') !== null) {
            $keyword = $request->get('rank');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('rank_id',$keyword);
            });
        }

        if ($request->get('status') !== null) {
            $keyword = $request->get('status');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('status',$keyword);
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
