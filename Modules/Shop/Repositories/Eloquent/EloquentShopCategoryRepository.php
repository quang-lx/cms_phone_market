<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\ShopCategoryRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Modules\Mon\Entities\Pcategory;
use Illuminate\Support\Facades\Auth;

class EloquentShopCategoryRepository extends BaseRepository implements ShopCategoryRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = Pcategory::query();

        if ($relations) {
            $query = $query->with($relations);
        }

        $query->where('type',"service");

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

    public function create_or_delete($request)
    {
        $data =  $this->newQueryBuilder()->where(['shop_id'=>Auth::user()->shop_id,'category'=>$request['id']])->first();
        if (isset($data)) {
            $data->delete();
        }else{
            $shopCategory=[
                'shop_id' => Auth::user()->shop_id,
                'category' => $request['id'],
                'type' => 'service',
            ];
            $this->create($shopCategory);
        }
    }
}
