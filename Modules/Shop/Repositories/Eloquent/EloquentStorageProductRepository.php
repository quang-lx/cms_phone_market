<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\StorageProductRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Events\StorageProduct\StorageProductWasUpdated;
use Illuminate\Http\Request;
use \Modules\Mon\Entities\StorageProduct;
use \Modules\Mon\Entities\VtShopProduct;

class EloquentStorageProductRepository extends BaseRepository implements StorageProductRepository
{

    public function update($model, $data)
	{
        if ($model['status'] != StorageProduct::STATUS_NEW){
            //neu trang thai != 1 ko xu ly gi
            return $model;
        }
		unset($data['updated_at']);
        // cập nhật trạng thái phiếu về 2
        $data['status'] = StorageProduct::STATUS_IMPORT;
		$model->update($data);
        event(new StorageProductWasUpdated($model, $data));

        //import vào bảng vt_shop_product
        foreach ($data['vtProducts'] as $product){
            $row = VtShopProduct::query()->where([
                'vt_product_id' => $product['id'],
                'shop_id' => $data['to_shop_id'],
                'company_id' => $data['company_id'],
            ])->first();
            if ($row) {
                $row->update([
                    'amount' => $row->amount + $product['count']

                ]);
            } else {
                VtShopProduct::create([
                    'vt_product_id' => $product['id'],
                    'shop_id' => $data['to_shop_id'],
                    'company_id' => $data['company_id'],
                    'amount' => $product['count'],
                ]);
            }
        }


		return $model;
	}

	public function serverPagingFor(Request $request, $relations = null) {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $code = $request->get('search');
            $query->where('title', 'LIKE', "%{$code}%");
        }

		$companyId = Auth::user()->company_id;
		$shopId = Auth::user()->shop_id;
		$query->where('company_id', $companyId)->where('to_shop_id', $shopId);

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }

}
