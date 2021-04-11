<?php

namespace Modules\Admin\Repositories\Eloquent;

use Modules\Admin\Repositories\BrandRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Mon\Entities\BrandPcategory;
use Illuminate\Http\Request;
class EloquentBrandRepository extends BaseRepository implements BrandRepository
{
    public function create($data)
    {
     
        $model =  $this->model->create($data);
        $model->pcategories()->sync($data['category_id']);
    }

    public function update($model, $data)
    {
        $model->update($data);
        $model->pcategories()->sync($data['category_id']);
    }

    public function destroy($model)
    {
        $model->delete();
        return $model->pcategories()->detach();
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
