<?php

namespace Modules\Shop\Repositories\Eloquent;

use App\Models\MPermission;
use Illuminate\Http\Request;
use Modules\Shop\Repositories\ShopRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentShopRepository extends BaseRepository implements ShopRepository
{

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        $query->where('module', MPermission::MODULE_ADMIN);
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('guard_name', 'LIKE', "%{$keyword}%")
                    ->orWhere('id', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->get('guard_name') !== null) {
            $query->where('guard_name', '=', $request->get('guard_name'));
        }

        if ($request->get('name') !== null) {
            $query->where('name', '=', $request->get('name'));
        }

        return $query->paginate($request->get('per_page', 10));
    }

}
