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
        $brand_pcategory=[];
        $brand_id = $this->model->create($data)->id;
        foreach ($data['category_id'] as $key => $value) {
           array_push($brand_pcategory,['brand_id'=>$brand_id,'pcategory_id'=>$value]);
        }
        BrandPcategory::insert($brand_pcategory);
    }

    public function update($model, $data)
    {
        $model->update($data);
        $brand_pcategory=[];
        $brand_id = $model->id;
        foreach ($data['category_id'] as $key => $value) {
           array_push($brand_pcategory,['brand_id'=>$brand_id,'pcategory_id'=>$value]);
        }
        BrandPcategory::where('brand_id', $brand_id)->delete();
        BrandPcategory::insert($brand_pcategory);
    }

    public function destroy($model)
    {
        $brand_id = $model->id;
        BrandPcategory::where('brand_id', $brand_id)->delete();
        return $model->delete();
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
