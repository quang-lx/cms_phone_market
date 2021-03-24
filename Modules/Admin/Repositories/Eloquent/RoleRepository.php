<?php
namespace Modules\Admin\Repositories\Eloquent;

use App\Models\MPermission;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Modules\Admin\Repositories\RoleRepository as RoleInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements RoleInterface {
    public function create($data)
    {
        $permissions= $data['permissions'];
        unset($data['permissions']);
        $model = $this->model->create($data);
        $model->syncPermissions($this->parsePermission($permissions));
        return $model;
    }

    public function update($model, $data)
    {
        $permissions= $data['permissions'];
        unset($data['permissions']);
        $model->update($data);
		$model->syncPermissions($this->parsePermission($permissions));
        return $model;
    }

    /**
     * @param $model Role
     * @param $permissions
     */
    public function addListPermission($model,$permissions) {
        $model->givePermissionTo($permissions);
        return $model;

    }
    /**
     * @param $model Role
     * @param $permissions
     */
    public function removeListPermission($model,$permissions) {
        $model->revokePermissionTo($permissions);
        return $model;

    }
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();

        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('guard_name', 'LIKE', "%{$keyword}%")
                    ->orWhere('id', 'LIKE', "%{$keyword}%");
            });
        }
        if ($request->get('module') !== null) {
            $query->where('module', '=', $request->get('module'));
        }
        if ($request->get('guard_name') !== null) {
            $query->where('guard_name', '=', $request->get('guard_name'));
        }

        if ($request->get('name') !== null) {
            $query->where('name', '=', $request->get('name'));
        }

        return $query->paginate($request->get('per_page', 10));
    }
    public function parsePermission($permissions) {
    	$result = [];
    	foreach ($permissions as $key => $value) {
    		if ($value == 1) {
    			$permisson = Permission::query()->where('name', $key)->first();
    			if ($permisson) {
					$result[] = $permisson->id;
				}

			}
		}
    	return $result;
	}
}
