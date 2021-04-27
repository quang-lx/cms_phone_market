<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\VoucherRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Admin\Events\Voucher\VoucherWasUpdated;

class EloquentVoucherRepository extends BaseRepository implements VoucherRepository
{
    public function create($data)
    {
        $data['company_id'] = Auth::user()->company_id;
        $model = $this->model->create($data);
        $data['products'] = array_map(function ($product){
            return $product['id'];
        }, $data['products']);
        if (isset($data['products']) && is_array($data['products'])) {
            $model->products()->sync($data['products']); //mảng product_id
        }

        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        $data['company_id'] = Auth::user()->company_id;
        // $model = $this->model->update($data);
        event(new VoucherWasUpdated($model, $data));
        $data['products'] = array_map(function ($product){
            return $product['id'];
        }, $data['products']);
        if (isset($data['products']) && is_array($data['products'])) {
            $model->products()->sync($data['products']); //mảng product_id
        }

        return $model;
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $code = $request->get('search');
            $query->where('code', 'LIKE', "%{$code}%");
        }

        $companyId = Auth::user()->company_id;
        $query->where('company_id', $companyId);

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }
}
