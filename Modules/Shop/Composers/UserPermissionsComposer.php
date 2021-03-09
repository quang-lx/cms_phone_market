<?php

namespace Modules\Shop\Composers;

use Illuminate\Contracts\View\View;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\User;

class UserPermissionsComposer
{
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function compose(View $view)
    {
        /** @var User $user */
        $user = $this->auth->user();
        $permissions = $user->getAllPermissions();

        $view->with('permissions', $permissions);
    }
}
