<?php

namespace Modules\Mon\Http\Middleware;

use Closure;
use Modules\Mon\Auth\Contracts\Authentication;
use Modules\Mon\Entities\User;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        /** @var Authentication $auth */
        $auth = app(Authentication::class);
        if (!$auth->check()) {
            return redirect()->guest(route('admin.login'))->withErrors('Vui lòng đăng nhập');
        }
        if ($auth->user()->type != User::TYPE_ADMIN ) {
            $auth->logout();
            return redirect()->guest(route('admin.login'))->withErrors(['username' => 'Vui lòng đăng nhâp bằng tài khoản quản trị!']);
        }
        return $next($request);
    }
}
