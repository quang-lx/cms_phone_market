<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Events\Pcategory\PCategoryWasCreated;
use Modules\Admin\Events\Pcategory\PCategoryWasUpdated;
use Modules\Admin\Repositories\PcategoryRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentPcategoryRepository extends BaseRepository implements PcategoryRepository
{
    protected $categories;
    protected $cate;
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
        // dd(1);
        return $data->setCollection(collect($data_case));
    }

    function formatCategories($data, $parent_id = null, $level = 0){
        $result = [];

        foreach($data as $key=>$item){
            if($item['parent_id'] == $parent_id){
                $item['level'] = $level;
                $result[] = $item;
                unset($data[$key]);
                $child = $this->formatCategories($data, $item['id'], $level + 1 );
                $result = array_merge($result, $child);
            }
        }
        // echo "<pre>";
        // print_r($result);
        return $result;
    }
}
