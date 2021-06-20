<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\TransferHistoryRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Events\Transfer\TransferWasCreated;
use Modules\Shop\Events\Transfer\TransferWasUpdated;
use Illuminate\Http\Request;
use Modules\Mon\Entities\TransferHistory;
use Modules\Mon\Entities\VtShopProduct;

class EloquentTransferHistoryRepository extends BaseRepository implements TransferHistoryRepository
{
    public static function createVtShopProductIfNull($data)
    {
        foreach ($data['vtProducts'] as $vtProduct) {
            VtShopProduct::query()->firstOrCreate([
                'shop_id' => $data['to_shop_id'], 
                'vt_product_id' => $vtProduct['id'],
                'company_id' => $data['company_id'],
            ]);
            if ($data['shop_id']){
                VtShopProduct::query()->firstOrCreate([
                    'shop_id' => $data['shop_id'], 
                    'vt_product_id' => $vtProduct['id'],
                    'company_id' => $data['company_id'],
                ]);
            }
        }
    }

    public static function updateVtShopProduct($data)
    {
        if ($data['type'] == TransferHistory::TYPE_EXPORT){
            foreach ($data['vtProducts'] as $vtProduct) {
                $query = VtShopProduct::query();
                if ($data['shop_id']){
                    $query->where('shop_id', $data['shop_id']);
                } else {
                    $query->whereNull('shop_id');
                }
                $query->where('company_id', $data['company_id'])
                    ->where('vt_product_id', $vtProduct['id'])
                    ->decrement('amount', intval($vtProduct['count']));
            }
        } else if ($data['type'] == TransferHistory::TYPE_MOVE){
            foreach ($data['vtProducts'] as $vtProduct) {
                $query = VtShopProduct::query();
                if ($data['shop_id']){
                    $query->where('shop_id', $data['shop_id']);
                } else {
                    $query->whereNull('shop_id');
                }
                $query->where('company_id', $data['company_id'])
                    ->where('vt_product_id', $vtProduct['id'])->decrement('amount', intval($vtProduct['count']));
                //push notify cho chủ kho nhận
            }

        }
    }

    public function create($data)
	{
        $data['company_id'] = Auth::user()->company_id;
        $data['shop_id'] = Auth::user()->shop_id;
		$model = $this->model->create($data);
        if (isset($data['vtProducts']) && is_array($data['vtProducts'])) {
            foreach ($data['vtProducts'] as $product) {
                if (!empty($productArr[$product['id']])){
                    $productArr[$product['id']]['amount'] += $product['count'];
                } else {
                    $productArr[$product['id']]['amount'] = $product['count'];  
                }
            }
            $model->vtProducts()->sync($productArr, false);

            
            //check nếu chưa có bản ghi vt_shop_product thì tạo mới
            self::createVtShopProductIfNull($data);

            //update vt_shop_product
            self::updateVtShopProduct($data);
        }

		return $model;
	}

    public function update($model, $data)
	{
		$model->update($data);
        $data['company_id'] = Auth::user()->company_id;
        $data['shop_id'] = Auth::user()->shop_id;
        event(new TransferWasUpdated($model, $data));
        if (isset($data['vtProducts']) && is_array($data['vtProducts'])) {
            foreach ($data['vtProducts'] as $product) {
                if (!empty($productArr[$product['id']])){
                    $productArr[$product['id']]['amount'] += $product['count'];
                } else {
                    $productArr[$product['id']]['amount'] = $product['count'];  
                }
            }
            $model->vtProducts()->detach();
            
            $model->vtProducts()->sync($productArr, false);
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
		$query->where('company_id', $companyId);

        if ($shopId) {
            $query->where('shop_id', $shopId);
        } else {
            if ($request->get('shop_id') !== null) {
                $shopId = $request->get('shop_id');
                $query->where('shop_id', $shopId);
            }
        }
        
        if ($request->get('to_shop_id') !== null) {
            $toShopId = $request->get('to_shop_id');
            $query->where('to_shop_id', $toShopId);
        }

        if ($request->get('type') !== null) {
            $type = $request->get('type');
            $query->where('type', $type);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }
}
