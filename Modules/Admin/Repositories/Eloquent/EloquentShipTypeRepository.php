<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\ShipTypeRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;

class EloquentShipTypeRepository extends BaseRepository implements ShipTypeRepository
{
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


        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name','LIKE', "%{$keyword}%");
            });
        }
        return $query->paginate($request->get('per_page', 10));    
    }
}
