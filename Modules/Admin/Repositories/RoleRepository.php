<?php
namespace Modules\Admin\Repositories;

use Modules\Mon\Repositories\BaseRepository;

interface RoleRepository extends BaseRepository{
    public function addListPermission($model,$permissions);
    public function removeListPermission($model,$permissions);
}
