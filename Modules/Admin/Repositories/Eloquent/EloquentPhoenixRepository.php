<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\PhoenixRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentPhoenixRepository extends BaseRepository implements PhoenixRepository
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
                    ->orWhere('code', 'LIKE', "%{$keyword}%")
                    ->orWhere('type', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('district', function($c) use ($keyword) {
                        $c->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhereHas('province', function($c) use ($keyword) {
                            $c->where('name', 'LIKE', "%{$keyword}%");
                        });
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
