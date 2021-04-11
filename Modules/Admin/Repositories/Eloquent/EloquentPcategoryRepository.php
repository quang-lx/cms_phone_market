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
            return  $data = $query->get();
        }

        if ($request->get('parent_id') !== null) {
            $id_edit = $request->get('id_edit');
            $query->where(function ($q) use ($id_edit){
                $q->where('parent_id',null);
                $q->where('id','!=',$id_edit);
            });
            return  $data = $query->paginate($request->get('per_page', 10));
        }

        $data = $query->paginate($request->get('per_page', 10));
        $data_case= $this->formatCategories($data->getCollection());
        return $data->setCollection(collect($data_case));
    }

    function formatCategories($data, $parent_id = null){
        $result = [];

        foreach($data as $key=>$item){
            if($item['parent_id'] == $parent_id){
                $result[] = $item;
                unset($data[$key]);
                $child = $this->formatCategories($data, $item['id']);
                $result = array_merge($result, $child);
            }
        }
        return $result;
    }

    public function destroy($model)
    {
        $model->delete();
        return $model->brand()->detach();
    }
}
