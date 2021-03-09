<?php

namespace Modules\Admin\Sidebar;


use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\ShouldCache;
use Maatwebsite\Sidebar\Sidebar;
use Maatwebsite\Sidebar\Traits\CacheableTrait;
use  Modules\Admin\Events\Sidebar\BuildingSidebar;

class AdminSidebar implements Sidebar, ShouldCache
{
    use CacheableTrait;
    /**
     * @var Menu
     */
    protected $menu;

    /**
     * @param Menu                $menu
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;

    }

    /**
     * Build your sidebar implementation here
     */
    public function build()
    {
        event(new BuildingSidebar($this->menu));

    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        $this->build();

        return $this->menu;
    }

}
