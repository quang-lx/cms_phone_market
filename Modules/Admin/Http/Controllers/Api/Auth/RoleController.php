<?php

namespace Modules\Admin\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\Role\CreateRoleRequest;
use Modules\Admin\Http\Requests\Role\UpdateRoleRequest;
use Modules\Admin\Transformers\Auth\FullRoleTransformer;
use Modules\Admin\Transformers\Auth\RoleNewTransformer;
use Modules\Admin\Transformers\Auth\RoleTransformer;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Repositories\PermissionRepository;
use Modules\Mon\Repositories\RoleRepository;
use Spatie\Permission\Models\Role;
use Modules\Mon\Auth\Contracts\Authentication;

class RoleController extends ApiController
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    public function __construct(
        Authentication $auth,
        RoleRepository $roleRepository
    ) {
        parent::__construct($auth);
        $this->roleRepository = $roleRepository;

    }

    public function all(Request $request)
    {
        return RoleTransformer::collection($this->roleRepository->newQueryBuilder()->get());
    }
    public function index(Request $request)
    {
        return RoleTransformer::collection($this->roleRepository->serverPagingFor($request));
    }

    public function store(CreateRoleRequest $request)
    {
        $role = $this->roleRepository->create($request->only('name', 'guard_name', 'permissions','description'));

        return response()->json([
            'errors' => false,
            'message' => trans('backend::role.message.create success'),
            'id' => $role->id
        ]);
    }

    public function find(Role $role)
    {
        return new  RoleTransformer($role);
    }
	public function findNew()
	{
		return new RoleNewTransformer(new Role(['guard_name'=> 'web']));
	}
    public function update(Role $role, UpdateRoleRequest $request)
    {
        $role = $this->roleRepository->update($role, $request->only('name', 'guard_name', 'permissions', 'description'));

        return response()->json([
            'errors' => false,
            'message' => trans('backend::role.message.update success'),
            'id' => $role->id
        ]);
    }

    public function destroy(Role $role)
    {
        $this->roleRepository->destroy($role);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::role.message.delete success'),
        ]);
    }
    public function assignPermissions(Role $role, Request $request)
    {
        $this->roleRepository->addListPermission($role, $request->get(  'permissions'));

        return response()->json([
            'errors' => false,
            'message' => trans('backend::role.message.add permissions success'),
        ]);
    }
    public function removePermissions(Role $role, Request $request)
    {
        $this->roleRepository->removeListPermission($role,$request->get(  'permissions'));

        return response()->json([
            'errors' => false,
            'message' => trans('backend::role.message.remove permissions success'),
        ]);
    }
}
