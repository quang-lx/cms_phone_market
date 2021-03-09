<?php

namespace Modules\Mon\Http\Middleware;

use Closure;

class SaveSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        \Settings::whenRequestEnds();
//        \Cart::save();
    }
}
