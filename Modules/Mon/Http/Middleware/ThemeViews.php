<?php

namespace Modules\Mon\Http\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Modules\Mon\Events\LoadingTranslations;

class ThemeViews
{
    protected $app;

    /** @var Factory */
    protected $views;

    public function __construct(Application $app, Factory $views)
    {
        $this->views = $views;
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $adminTheme = 'backend';
        $this->views->addNamespace($adminTheme, base_path('themes/' . $adminTheme . '/views'));

        $frontendTheme = 'base';
        $this->views->addNamespace($frontendTheme, base_path('themes/' . $frontendTheme . '/views'));



        $shopTheme = 'shop';
        $this->views->addNamespace($shopTheme, base_path('themes/' . $shopTheme . '/views'));

        // Needed to clear the views cache.
        $this->views->getFinder()->flush();

        return $next($request);
    }
}
