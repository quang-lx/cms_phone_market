<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\CompanyRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Admin\Events\Company\CompanyWasCreated;
use Modules\Admin\Events\Company\CompanyWasUpdated;
class EloquentCompanyRepository extends BaseRepository implements CompanyRepository
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
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('users', function ($c) use ($keyword) {
                        $c->where('username', 'LIKE', "%{$keyword}%")
                            ->where('is_admin_company', 1);
                    });
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }

    public function create($data)
    {
        $model = $this->model->create($data);
        event(new CompanyWasCreated($model, $data));
        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new CompanyWasUpdated($model, $data));
        return $model;
    }
}
