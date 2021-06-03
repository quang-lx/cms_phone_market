<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Events\Category\CategoryWasCreated;
use Modules\Admin\Events\Category\CategoryWasUpdated;
use Modules\Admin\Repositories\CategoryRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Shop\Repositories\PcategoryRepository;

class EloquentPcategoryRepository extends BaseRepository implements PcategoryRepository
{


    public function serverPagingForTree(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        $categories = $query->select('id','name AS label')
            ->whereNull('parent_id')->get()->toArray();
        foreach ($categories as $key => $category){
            $query = $this->newQueryBuilder();
            $catChild = $query->select('id','name AS label')
                ->where('parent_id', $category['id'])->get()->toArray();
            $categories[$key]['children'] = $catChild;
        }
        return ($categories);
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        $categories = $query->select('id','name')->get()->toArray();
        return ($categories);
    }
	public function getList(Request $request, $relations = null)
		{
			$query = $this->newQueryBuilder();
			if ($relations) {
				$query = $query->with($relations);
			}
			$categories = $query->select('id','name')->whereNull('parent_id')->get()->toArray();
			return ($categories);
		}

}
