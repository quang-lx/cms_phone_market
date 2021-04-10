<?php

namespace Modules\Shop\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Mon\Entities\User;
use Modules\Mon\Events\UserWasCreated;
use Modules\Mon\Events\UserWasUpdated;
use Modules\Shop\Repositories\UserRepository;
use \Modules\Mon\Repositories\Eloquent\BaseRepository;
use Ramsey\Uuid\Uuid;

class EloquentUserRepository extends BaseRepository implements UserRepository
{
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        $query->where('company_id', Auth::user()->company_id);
        if (Auth::user()->shop_id){
            $query->where('shop_id', Auth::user()->shop_id);
        }

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('username', 'LIKE', "%{$keyword}%")
                    ->orWhere('phone', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('id', 'desc');
        }
        return $query->paginate($request->get('per_page', 10));
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
}
