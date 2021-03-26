<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\DistrictRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;

class EloquentDistrictRepository extends BaseRepository implements DistrictRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('lat', 'LIKE', "%{$keyword}%")
                    ->orWhere('lng', 'LIKE', "%{$keyword}%")
                    ->orWhere('code', 'LIKE', "%{$keyword}%")
                    ->orWhere('type', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('province', function($c) use ($keyword) {
                        $c->where('name', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'asc');
        }

        return $query->paginate($request->get('per_page', 10));
    }
}
