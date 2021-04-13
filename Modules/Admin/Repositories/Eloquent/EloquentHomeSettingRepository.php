<?php

namespace Modules\Admin\Repositories\Eloquent;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\HomeSettingRepository;
use Modules\Mon\Entities\HomeSetting;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;

class EloquentHomeSettingRepository extends BaseRepository implements HomeSettingRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        $query->orderBy('order_', 'asc');
        return $query->paginate($request->get('per_page', 100));
    }

    public function store($data)
    {
        if(isset($data['blocks'])) {
            $blocks = $data['blocks'];
            if (is_array($blocks)) {
                $oldId = [];
                foreach ($blocks as $item) {
                    if (isset($item['id']) && $item['id']) {
                        $oldId[] = $item['id'];
                    }
                }
                if (count($oldId)> 0) {
                    HomeSetting::query()->whereNotIn('id', $oldId)->delete();
                }

                foreach ($blocks as $key => $item) {
                    $item['order_'] = $key;
                    if (isset($item['id']) && $item['id']) {
                        $oldId = $item['id'];
                        $model = HomeSetting::query()->where('id', $oldId)->update($item);
                    } else {
                        HomeSetting::create($item);
                    }
                }
            }
        }

    }
}
