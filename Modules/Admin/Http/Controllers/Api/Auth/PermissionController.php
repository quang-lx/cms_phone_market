<?php

namespace Modules\Admin\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\Permission\CreatePermissionRequest;
use Modules\Admin\Http\Requests\Permission\UpdatePermissionRequest;
use Modules\Admin\Transformers\Auth\PermissionTransformer;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Admin\Repositories\PermissionRepository;
use Spatie\Permission\Models\Permission;
use Modules\Mon\Auth\Contracts\Authentication;

class PermissionController extends ApiController
{
    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;
    public function __construct(
        Authentication $auth,
        PermissionRepository $permissionRepository
    ) {
        parent::__construct($auth);
        $this->permissionRepository = $permissionRepository;

    }
	public function allByGroup(Request $request)
	{
		return  $this->permissionRepository->serverPagingForGroup($request);
	}
    public function index(Request $request)
    {
        return PermissionTransformer::collection($this->permissionRepository->serverPagingFor($request));
    }

    public function store(CreatePermissionRequest $request)
    {
        $this->permissionRepository->create($request->only('name', 'guard_name'));

        return response()->json([
            'errors' => false,
            'message' => trans('backend::permission.message.create success'),
        ]);
    }

    public function find(Permission $permission)
    {
        return new  PermissionTransformer($permission);
    }

    public function update(Permission $permission, UpdatePermissionRequest $request)
    {
        $this->permissionRepository->update($permission, $request->only('name', 'guard_name'));

        return response()->json([
            'errors' => false,
            'message' => trans('backend::permission.message.update success'),
        ]);
    }

    public function destroy(Permission $permission)
    {
        $this->permissionRepository->destroy($permission);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::permission.message.delete success'),
        ]);
    }
}
