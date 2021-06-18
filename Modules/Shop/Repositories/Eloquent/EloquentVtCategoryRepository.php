<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Events\VtCategory\VtCategoryWasCreated;
use Modules\Admin\Events\VtCategory\VtCategoryWasUpdated;
use Modules\Shop\Repositories\VtCategoryRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentVtCategoryRepository extends BaseRepository implements VtCategoryRepository
{
    protected $categories;

    public function create($data)
    {
        $model = $this->model->create($data);
        event(new VtCategoryWasCreated($model, $data));
        return $model;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new VtCategoryWasUpdated($model, $data));
        return $model;
    }
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        
		$user = Auth::user();
		$query->where('company_id', $user->company_id);
        $notCheckShop = $request->get('not_check_shop');
		if($user->shop_id & !$notCheckShop) {
			$query->where('shop_id', $user->shop_id);
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
                $q->orWhere('type', $type);
            });
            return  $data = $query->get();
        }

        if ($request->get('parent_id') !== null) {
            $id_edit = $request->get('id_edit');
            $query->where(function ($q) use ($id_edit) {
                $q->where('parent_id', null);
                $q->where('id', '!=', $id_edit);
            });
            return  $data = $query->paginate($request->get('per_page', 10));
        }
        $data = $query->paginate($request->get('per_page', 10));
        $dataTotal = (clone $query)->limit(1000)->offset(0)->get();
        $data_case = $this->formatCategories($dataTotal);
        $data_return = $this->getDataForPaging($data_case, $request->get('page', 1), $request->get('per_page', 10));
        return $data->setCollection(collect($data_return));
    }

    function formatCategories($data, $parent_id = null)
    {
        $result = [];

        foreach ($data as $key => $item) {
            if ($item['parent_id'] == $parent_id) {
                $result[] = $item;
                unset($data[$key]);
                $child = $this->formatCategories($data, $item['id']);
                $result = array_merge($result, $child);
            }
        }
        return $result;
    }
    public function getDataForPaging($data, $row, $per_page)
    {
        $from = (($row > 0 ? $row : 1) - 1) * $per_page;
        return array_slice($data, $from, $per_page);
    }

}
