<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\AttributeRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
class EloquentAttributeRepository extends BaseRepository implements AttributeRepository
{
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

    public function create($data)
    {
        $attr_value = [];
        foreach ($data['list_attribute_value'] as $list_name) {
            array_push($attr_value,['name'=>$list_name]);
        }
        $model =  $this->model->create($data);
        $model->attributeValues()->createMany($attr_value);


    }

    public function update($model,$data)
    {
        $model->update($data);
        $model->attributeValues()->delete();
        $attr_value = [];
        foreach ($data['list_attribute_value'] as $list_name) {
            array_push($attr_value,['name'=>$list_name]);
        }
        $model->attributeValues()->createMany($attr_value);


    }

    public function destroy($model)
    {
        $model->delete();
        $model->attributeValues()->delete();
    }
}
