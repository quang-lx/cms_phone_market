<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\OrdersRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class EloquentOrdersRepository extends BaseRepository implements OrdersRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $search = $request->get('search');
            $query->where('id', 'LIKE', "%{$search}%");
            $query->orWhereHas('user', function ($c) use ($search) {
                $c->where('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($request->get('searchDate') !== null) {
            $searchDate = $request->get('searchDate');
            $query->where(function ($c) use ($searchDate) {
                $c->where('created_at', '>=', $searchDate[0]);
                $c->where('created_at', '<=', $searchDate[1]);
            });

        }

        if ($request->get('shop') !== null) {
            $shop = $request->get('shop');
            $query->where(function ($c) use ($shop) {
                $c->where('shop_id', '=', $shop);
            });

        }

        if ($request->get('order_type') !== null) {
            $order_type = $request->get('order_type');
            $query->where(function ($c) use ($order_type) {
                $c->where('order_type', '=', $order_type);
            });

        }

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where(function ($c) use ($status) {
                $c->where('status', '=', $status);
            });

        }

		$user = Auth::user();
		$query->where('company_id', $user->company_id);
        if($user->shop_id) {
			$query->where('shop_id', $user->shop_id);
		}


        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }

    public function update($model, $data)
    {
        $data_update=[];
        if($data['type']=='sua_chua'){
            $data_update['total_price'] = $data['price'];
            $data_update['pay_price'] = $data['price'];
            $data_update['fix_time'] = $data['numberDate'];
            $data_update['fix_time_date'] = Carbon::now()->addDays($data['numberDate'])->toDateTimeString();
            $data_update['status'] = 'wait_client';
        }
        
        $model->update($data_update);
    }
}
