<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Repositories\ShopOrderNotificationRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentShopOrderNotificationRepository extends BaseRepository implements ShopOrderNotificationRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        $query->where('shop_id', Auth::user()->shop_id);

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('seen','asc')->orderBy('id','desc');
        }

        return $query->paginate(5);
    }

    public function update($model, $data)
    {
        $model->update($data);
        return $model;
    }
}
