<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Mon\Entities\VtProduct;
use Modules\Mon\Entities\VtShopProduct;
use Modules\Shop\Repositories\VtShopProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentVtShopProductRepository extends BaseRepository implements VtShopProductRepository
{
    public function create($data)
    {
        $company_id = Auth::user()->company_id;
        $shop_id = Auth::user()->shop_id;
        $vtshopproduct = VtShopProduct::firstOrNew(
            [
                'vt_product_id'=>$data['vt_product_id'],
                'shop_id'=>$shop_id,
                'company_id'=>$company_id,          
            ]);
        $vtshopproduct->amount = ($vtshopproduct->amount + $data['amount']);
        return $vtshopproduct->save();
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhereHas('vtProudct', function ($c) use ($keyword) {
                    $c->where('name', 'LIKE', "%{$keyword}%");
                });
            });
        }
        if ($request->get('shop_id') !== null) {
            $shop_id = $request->get('shop_id');
            $query->where('shop_id',$shop_id);
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
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }


}
