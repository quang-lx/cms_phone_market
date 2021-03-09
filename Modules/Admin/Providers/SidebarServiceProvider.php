<?php
namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Sidebar\SidebarManager;
use Modules\Admin\Sidebar\AdminSidebar;

class SidebarServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
     }

    public function boot(SidebarManager $manager)
    {
        $manager->register(AdminSidebar::class);
    }
}
