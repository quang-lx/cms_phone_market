<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Events\PCategory\PCategoryWasCreated;
use Modules\Admin\Events\PCategory\PCategoryWasUpdated;
use Modules\Admin\Repositories\PcategoryRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentPcategoryRepository extends BaseRepository implements PcategoryRepository
{
    protected $categories;

    public function create($data)
    {
        $model = $this->model->create($data);
        event(new PCategoryWasCreated($model, $data));
        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new PCategoryWasUpdated($model, $data));
        return $model;
    }
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('created_at', 'asc');
        }

        if ($request->get('type') !== null) {
            $type = $request->get('type');
            $query->where(function ($q) use ($type) {
                $q->orWhere('type',$type);
            });
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name','LIKE', "%{$keyword}%");
            });
        }
        return $query->paginate($request->get('per_page', 10));
        

        
    }

    public function destroy($model)
    {
        $model->delete();
        return $model->brand()->detach();
    }
}
