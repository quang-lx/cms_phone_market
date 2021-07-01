<?php

namespace Modules\Shop\Repositories\Eloquent;

use Modules\Shop\Repositories\PaymentMethodRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;

class EloquentPaymentMethodRepository extends BaseRepository implements PaymentMethodRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        $query->orderBy('id', 'desc');

        return $query->paginate($request->get('per_page', 10));
    }
}
