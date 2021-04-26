<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\ProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentProductRepository extends BaseRepository implements ProductRepository
{

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

		if ($request->get('category_id') !==null) {
			$catId = $request->get('category_id');
			$query->join('category_product','product.id','=','category_product.product_id')
				->where('category_product.category_id',$catId)->select('product.*');
		}

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku', 'LIKE', "%{$keyword}%");
            });
        }

       if ($request->get('brand_id') !==null) {
           $brandId = $request->get('brand_id');
           $query->where('brand_id', $brandId);
       }

       if ($request->get('company_id') !==null) {
            $companyId = $request->get('company_id');
            $query->where('company_id', $companyId);
        }

	   if ($request->get('source') == 'voucher'){
		   $query->select('product.*','product.name AS value');
	   }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('product.id', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }
}
