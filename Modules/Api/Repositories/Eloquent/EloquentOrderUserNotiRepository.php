<?php

namespace Modules\Api\Repositories\Eloquent;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Repositories\OrderUserNotiRepository;
use Modules\Api\Repositories\RatingRepository;


class EloquentOrderUserNotiRepository extends ApiBaseRepository implements OrderUserNotiRepository
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }



    public function getList(Request $request)
    {
    	$userId = Auth::user()->id;
        $query = $this->model->query();
        $query->where('user_id', $userId);
        $query->orderByDesc('id');
        return $query->paginate($request->get('per_page', 10));

    }

}
