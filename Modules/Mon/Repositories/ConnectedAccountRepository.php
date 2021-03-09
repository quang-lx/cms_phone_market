<?php
namespace Modules\Mon\Repositories;

use Modules\Mon\Entities\User;
use Modules\Mon\Repositories\BaseRepository;
interface ConnectedAccountRepository extends BaseRepository {
     public function getUserBySocialAccount($socialAccountId, $provider);
}
