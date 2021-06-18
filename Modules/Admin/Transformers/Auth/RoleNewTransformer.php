<?php

namespace Modules\Admin\Transformers\Auth;

namespace Modules\Admin\Transformers\Auth;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;


class RoleNewTransformer extends JsonResource
{


    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'guard_name' => $this->guard_name,
            'created_at' => optional($this->created_at)->format('d-m-Y'),
            'updated_at' => optional($this->updated_at)->format('d-m-Y'),
            'permissions' => $this->getPermissions(),

        ];


        return $data;
    }

    public function getPermissions()
    {
        /**
         * @var $hasPermissions Collection
         */
        $hasPermissions = $this->permissions;
        $allPermissions = Permission::query()->get();
        $result = [];
        foreach ($allPermissions as $permission) {
            if ($hasPermissions->where('id', $permission->id)->count() > 0) {
                $result[$permission->name] = 1;
            } else {
                $result[$permission->name] = -1;
            }

        }
        return $result;
    }
}
