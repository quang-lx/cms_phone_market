<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Mon\Entities\ProductPrice;
use Modules\Shop\Repositories\ShopRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Events\Shop\ShopWasCreated;
use Modules\Shop\Events\Shop\ShopWasUpdated;
class EloquentShopRepository extends BaseRepository implements ShopRepository
{


    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('phone', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->get('company_id') !==null) {
            $company_id = $request->get('company_id');
            $keyword = $request->get('search');
            $query->orWhereHas('company', function($c) use ($company_id) {
                $c->where('id',$company_id);
            });
        }

        if ($request->get('check_company')){
            $query->where('company_id', Auth::user()->company_id);
        }
        if ($request->get('shop_admin') !==null) {
            $query->where(function($c){
                $c->orWhere('company_id',Auth::user()->company_id);
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

    public function create($data)
    {
        $model = $this->model->create($data);
        event(new ShopWasCreated($model, $data));
        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new ShopWasUpdated($model, $data));
        return $model;
    }

}
