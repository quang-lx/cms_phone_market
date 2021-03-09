<?php

namespace Modules\Mon\Auth;

class Authentication implements \Modules\Mon\Auth\Contracts\Authentication
{

    /** @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard|AccessTokenGuard  */
    protected $guard;

    public function __construct($guard = null)
    {
        $this->setGuard($guard);
    }

    public function setGuard($guard)
    {
        $this->guard = \Auth::guard($guard);
    }

    public function getGuard()
    {
        return $this->guard;
    }

    /**
     * Determines if the current user has access to given permission
     * @param $permission
     * @return bool
     */
    public function hasAccess($permission)
    {
        $user = $this->user();
        if ($user) {
            return $user->can($permission);
        }
        return false;
    }

    /**
     * Check if the user is logged in
     * @return bool
     */
    public function check()
    {
        return $this->guard->check();
    }

    /**
     *
     * Get the currently logged in user
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Modules\Mon\Entities\User|null
     */
    public function user()
    {
        if ($this->check()) {
            return $this->guard->user();
        }
        return null;
    }

    /**
     * Get the ID for the currently authenticated user
     * @return int
     */
    public function id()
    {
        if ($user = $this->user()) {
            $user->id;
        }
        return null;
    }

    public function guest()
    {
        return $this->guard->guest();
    }

    public function logout()
    {
        $this->guard->logout();
    }
}
