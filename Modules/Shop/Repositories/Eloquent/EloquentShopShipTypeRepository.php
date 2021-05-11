<?php

namespace Modules\Shop\Repositories\Eloquent;

use App\Models\CacheKey;
use Modules\Shop\Repositories\ShopShipTypeRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Modules\Mon\Entities\ShipType;
use Illuminate\Support\Facades\Auth;

class EloquentShopShipTypeRepository extends BaseRepository implements ShopShipTypeRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = ShipType::query();

        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $code = $request->get('search');
            $query->where('code', 'LIKE', "%{$code}%");
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }
        return $query->paginate($request->get('per_page', 10));
    }

    public function create_or_update($data)
    {
        $data =  $this->newQueryBuilder()->firstOrNew(['shop_id'=>Auth::user()->shop_id,'ship_type_id'=>$data['id']]);
        $data->shop_id =Auth::user()->shop_id;
        $data->status = $data->status == 2?1:2;
        $data->save();
	    $cacheKey = sprintf(CacheKey::SHIP_TYPE_SHOP, $data->shop_id);
    }
}
