<?php

namespace Modules\Shop\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Modules\Shop\Events\Sidebar\Admin\UserSidebarExtender;
use Modules\Shop\Events\Sidebar\BuildingSidebar;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BuildingSidebar::class => [
            UserSidebarExtender::class,

        ],

    ];
}
