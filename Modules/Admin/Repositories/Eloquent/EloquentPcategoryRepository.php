<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\PcategoryRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentPcategoryRepository extends BaseRepository implements PcategoryRepository
{
    protected $categories;
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
            $query->orderBy('created_at', 'desc');
        }
        $this->categories = $query->paginate($request->get('per_page', 10));
        $this->showCategories($this->categories->toArray()['data']);
        dd($this->categories->toArray()['data']);
        return $query->paginate($request->get('per_page', 10));
    }

    function showCategories($categories, $parent_id = null)
    {
        foreach ($categories as $key => $item) {
            if ($item['parent_id'] == $parent_id) {
                $this->categories[$key]['children'] = $item;
                unset($this->categories[$key]);
                $this->showCategories($categories, $item['id']);
            }
        }
    }
}
