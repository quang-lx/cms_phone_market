<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Repositories\UserRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentUserRepository extends BaseRepository implements UserRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        $query->where('company_id', Auth::user()->company_id);
        if (Auth::user()->shop_id){
            $query->where('shop_id', Auth::user()->shop_id);
        }

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('username', 'LIKE', "%{$keyword}%")
                    ->orWhere('phone', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'asc');
        }
        $data = $query->paginate($request->get('per_page', 10));

        $data_update= $this->getCreatedName($data->getCollection());
        return $data->setCollection(collect($data_update));
    }

    function getCreatedName($data){
        $result = [];
        $query = $this->newQueryBuilder();

        foreach($data as $key=>$item){
            $item['created_name'] = $query->select('name')->where('id' , $item['created_by'])->first()->name;
            $result[] = $item;
        }
        return $result;
    }
}
