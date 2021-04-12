<?php

namespace Modules\Mon\Repositories\Eloquent;

use Illuminate\Support\Facades\Hash;
use Modules\Mon\Repositories\UserRepository as UserInterface;
use Modules\Mon\Events\UserWasCreated;
use Modules\Mon\Events\UserWasDeleting;
use Modules\Mon\Events\UserWasUpdated;
use Modules\Mon\Entities\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class UserRepository extends BaseRepository implements UserInterface
{

    public function create($data)
    {
        $user = $this->model->create($data);
        event(new UserWasCreated($user, $data));
        return $user;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new UserWasUpdated($model, $data));
        return $model;
    }

    public function destroy($model)
    {
        $model->username= sprintf('deleted_%d_%s',date('YmdHis'),$model->username);
        $model->email= sprintf('deleted_%d_%s',date('YmdHis'),$model->email);
        $model->save();
        event(new UserWasDeleting($model));
        return $model->delete();
    }


    public function changePassword($model, $data) {
        $this->checkForNewPassword($data);
        $model->update($data);
        return $model;
    }

    /**
     * Create a user and assign roles to it
     * @param  array $data
     * @param  array $roles
     * @return User
     */
    public function createWithRoles($data, $roles)
    {
        $this->checkForNewPassword($data);

        $user = $this->model->create((array)$data);
        event(new UserWasCreated($user, $data));
        if (!empty($roles)) {
            $user->assignRole($roles);
        }

        return $user;
    }
    /**
     * @param $userId
     * @param $data
     * @param $roles
     * @internal param $user
     * @return mixed
     */
    public function updateAndSyncRoles($model, $data, $roles)
    {
        unset($data['password']);
        $model->update($data);

        event(new UserWasUpdated($model, $data));

        if (!empty($roles)) {
            $model->syncRoles($roles);
        }
        return $model;
    }
    /**
     * Check if there is a new password given
     * If not, unset the password field
     * @param array $data
     */
    private function checkForNewPassword(array &$data)
    {
        if (array_key_exists('password', $data) === false) {
            return;
        }

        if ($data['password'] === '' || $data['password'] === null) {
            unset($data['password']);

            return;
        }

        $data['password'] = Hash::make($data['password']);
    }
    public function generateTokenFor(User $user)
    {
        return app(\Modules\Mon\Repositories\UserTokenRepository::class)->create([
            'user_id' => $user->id,
            'access_token' => Uuid::uuid4()->toString()
        ]);
    }

    /**
     *
     * @param Request $request
     * @param null $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {

                $q->orWhere('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('id', 'LIKE', "%{$keyword}%")
                    ->orWhere('username', 'LIKE', "%{$keyword}%");
            });
        }
	    if ($request->get('status') !== null) {
		    $status = $request->get('status');
		    $query->where('status', $status);
	    }
        $type = $request->get('type', User::TYPE_USER);
        $query->where('type', $type);


        if ($request->get('name') !== null) {
            $name = $request->get('name');
            $query->where('name', 'LIKE', "%{$name}%");
        }

        if ($request->get('email') !== null) {
            $email = $request->get('email');
            $query->where('email', 'LIKE', "%{$email}");
        }

        if ($request->get('role_name') !== null) {
            $roleName = $request->get('role_name');
            $query->whereHas('roles', function ($q) use ($roleName) {
                $q->where('name', '=', $roleName);
            });
        }

        if ($request->get('role_id') !== null) {
            $roleId = $request->get('role_id');
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('id', '=', $roleId);
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if ($request->get('group_by') !== null) {
            $query->groupBy(explode(",", $request->get('group_by')));
        }
        return $query->paginate($request->get('per_page', 10));
    }
}
