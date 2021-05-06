<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\TransferHistoryRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Events\Transfer\TransferWasCreated;
use Modules\Shop\Events\Transfer\TransferWasUpdated;
use Illuminate\Http\Request;

class EloquentTransferHistoryRepository extends BaseRepository implements TransferHistoryRepository
{
    public function create($data)
	{
        $data['company_id'] = Auth::user()->company_id;
		$model = $this->model->create($data);
        if (isset($data['products']) && is_array($data['products'])) {
            foreach ($data['products'] as $product) {
                $productArr[$product['id']] = ['amount' => $product['count']];  
            }
            
            $model->products()->sync($productArr, false);
        }

		return $model;
	}

    public function update($model, $data)
	{
		$model->update($data);
        $data['company_id'] = Auth::user()->company_id;
        event(new TransferWasUpdated($model, $data));
        if (isset($data['products']) && is_array($data['products'])) {
            foreach ($data['products'] as $product) {
                $productArr[$product['id']] = ['amount' => $product['count']];  
            }
            $model->products()->detach();
            
            $model->products()->sync($productArr, false);
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
		$query->where('company_id', $companyId);

        if ($request->get('shop_id') !== null) {
            $shopId = $request->get('shop_id');
            $query->where('shop_id', $shopId);
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
