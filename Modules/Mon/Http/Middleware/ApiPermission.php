<?php

namespace Modules\Mon\Http\Middleware;

use Closure;
use Modules\Mon\Auth\Contracts\Authentication;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ApiPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        /** @var Authentication $auth */
        $auth = app(Authentication::class);
        $auth->setGuard('token');

        if ($auth->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if ($auth->user()->can($permission)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
