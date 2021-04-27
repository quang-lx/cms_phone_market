<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\PInformationRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EloquentPInformationRepository extends BaseRepository implements PInformationRepository
{
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

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('title','LIKE', "%{$keyword}%");
            });
        }
        return $query->paginate($request->get('per_page', 10));
        

        
    }
    public function create($data)
    {
    	$data['company_id'] = Auth::user()->id;
    	$data['shop_id'] = Auth::user()->shop_id;
        $model =  $this->model->create($data);
        $model->attributeValues()->createMany($data['list_attribute_value']);


    }
}
