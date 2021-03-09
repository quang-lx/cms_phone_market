<?php

namespace Modules\Mon\Auth\Contracts;

interface Authentication
{
    public function setGuard($guard);
    public function getGuard();
    /**
     * Determines if the current user has access to given permission
     * @param $permission
     * @return bool
     */
    public function hasAccess($permission);

    /**
     * Check if the user is logged in
     * @return bool
     */
    public function check();

    /**
     * Get the currently logged in user
     * @return \Modules\Mon\Entities\User
     */
    public function user();

    /**
     * Get the ID for the currently authenticated user
     * @return int
     */
    public function id();

    public function guest();

    public function logout();
}
