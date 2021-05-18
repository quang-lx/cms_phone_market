<?php

namespace Modules\Admin\Repositories\Eloquent;

use App\Models\CacheKey;
use Illuminate\Support\Facades\Cache;
use Modules\Admin\Repositories\PaymentMethodRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;

class EloquentPaymentMethodRepository extends BaseRepository implements PaymentMethodRepository
{
	public function create($data)
	{
		$model = $this->model->create($data);
		Cache::forget(CacheKey::PAYMENT_METHOD_ALL);
		return $model;
	}

	public function update($model, $data)
	{
		$model->update($data);
		Cache::forget(CacheKey::PAYMENT_METHOD_ALL);
		return $model;
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
                $q->orWhere('name', 'LIKE', "%{$keyword}%");
            });
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
