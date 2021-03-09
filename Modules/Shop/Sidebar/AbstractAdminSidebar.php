<?php

namespace Modules\Shop\Sidebar;

use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\SidebarExtender;
use  Modules\Admin\Events\Sidebar\BuildingSidebar;
use  Modules\Mon\Auth\Contracts\Authentication;

abstract class AbstractAdminSidebar  implements SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    abstract public function extendWith(Menu $menu);
}
