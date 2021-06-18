<?php

namespace Modules\Api\Repositories\Eloquent;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        $notifications = $query->paginate($request->get('per_page', 10));
        $currentItems = $notifications->items();
        $ids = [];
        foreach ($currentItems as $item) {
        	$ids[] = $item->id;
        }
        $this->model->newQuery()->whereIn('id',$ids)->update([
        	'seen' => 1
        ]);
        return $notifications;

    }

}
