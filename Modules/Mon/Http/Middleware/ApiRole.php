<?php

namespace Modules\Mon\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;
use Modules\Mon\Auth\Contracts\Authentication;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ApiRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        /** @var Authentication $auth */
        $auth = app(Authentication::class);
        $auth->setGuard('token');

        if ($auth->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (!$auth->user()->hasAnyRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}
