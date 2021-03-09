<?php
namespace Modules\Mon\Repositories;

use Modules\Mon\Entities\User;
use Modules\Mon\Repositories\BaseRepository;
interface UserRepository extends BaseRepository {
    public function generateTokenFor(User $user);
    public function createWithRoles($data, $roles);
    public function updateAndSyncRoles($model, $data, $roles);
    public function changePassword($user,$data);
}
