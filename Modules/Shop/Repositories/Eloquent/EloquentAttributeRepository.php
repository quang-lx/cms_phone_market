<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\AttributeRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Modules\Mon\Entities\AttributeValue;
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
        $model =  $this->model->create($data);
        $model->attributeValues()->createMany($data['list_attribute_value']);


    }

    public function update($model,$data)
    {
        $model->update($data);
        $attr_value_old = $model->attributeValues()->pluck('id')->toArray();
        $attr_value_edit = [];
        $attr_value_add = [];
        foreach ($data['list_attribute_value'] as $list_name) {
            if (!empty($list_name['id'])) {
                array_push($attr_value_edit,$list_name['id']);
                AttributeValue::where('id',$list_name['id'])->update(['name'=>$list_name['name']]);
            }else{
                array_push($attr_value_add,['name'=>$list_name['name']]);
            }
        }
        $attr_value_delete =array_diff($attr_value_old,$attr_value_edit);
        if (!empty($attr_value_delete)) {
            AttributeValue::whereIn('id',$attr_value_delete)->delete();
        }    
        $model->attributeValues()->createMany($attr_value_add);


    }

    public function destroy($model)
    {
        $model->delete();
        $model->attributeValues()->delete();
    }
}
