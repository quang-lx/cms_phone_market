<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Shop\Repositories\BrandRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentBrandRepository extends BaseRepository implements BrandRepository
{

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        $categories = $query->select('id','name','type')->get()->toArray();
        return ($categories);
    }

}
