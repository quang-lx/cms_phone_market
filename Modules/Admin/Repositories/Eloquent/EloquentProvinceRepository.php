<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\ProvinceRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EloquentProvinceRepository extends BaseRepository implements ProvinceRepository
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
                    ->orWhere('code', 'LIKE', "%{$keyword}%")
                    ->orWhere('type', 'LIKE', "%{$keyword}%")
                    ->orWhere('phone_code', 'LIKE', "%{$keyword}%");

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
