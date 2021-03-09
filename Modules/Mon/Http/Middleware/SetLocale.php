<?php

namespace Modules\Mon\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class SetLocale
{
    protected $app;
    protected $redirector;
    protected $request;

    public function __construct(Application $app, Redirector $redirector, Request $request)
    {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = locale_prefix();
        if ($locale !== null) {
            $this->app->setLocale($locale);
        } else {
            $locale = config('app.locale');
        }
        $locales = locales();
        $this->app->config->set('translatable.locale', $locale);
        $this->app->config->set('translatable.locales', $locales);
        return $next($request);
    }

    protected function redirectToLocale(Request $request, $locale) {
        $segments = [];
        $segments[0] = $locale;
        foreach($request->segments() as $i => $seg) {
            $segments[$i+1] = $seg;
        }
        return $this->redirector->to(implode('/', $segments));
    }
}
