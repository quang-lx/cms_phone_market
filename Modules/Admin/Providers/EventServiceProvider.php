<?php

namespace Modules\Admin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Admin\Events\Sidebar\Admin\GeneralSettingSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\ListSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\MediaSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\NewsSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\PartnerSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\RaceEventSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\ResourceSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\SaleSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\SiteSettingSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\StandingSidebarExtender;
use Modules\Admin\Events\Sidebar\Admin\UserSidebarExtender;
use Modules\Admin\Events\Sidebar\BuildingSidebar;
use Modules\Mon\Entities\News;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BuildingSidebar::class => [
            UserSidebarExtender::class,
            MediaSidebarExtender::class,

        ],

    ];
}