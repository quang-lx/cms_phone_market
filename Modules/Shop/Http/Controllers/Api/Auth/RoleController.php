<?php

namespace Modules\Shop\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Modules\Shop\Http\Requests\Role\CreateRoleRequest;
use Modules\Shop\Http\Requests\Role\UpdateRoleRequest;
use Modules\Shop\Repositories\RoleRepository;
use Modules\Shop\Transformers\Auth\RoleNewTransformer;
use Modules\Shop\Transformers\Auth\RoleTransformer;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Http\Controllers\ApiController;
use Spatie\Permission\Models\Role;

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
        $role = $this->roleRepository->create($request->only('name', 'guard_name', 'permissions','description', 'module'));

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
