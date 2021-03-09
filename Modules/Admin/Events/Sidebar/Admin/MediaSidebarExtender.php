<?php

namespace Modules\Admin\Events\Sidebar\Admin;


use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Admin\Sidebar\AbstractAdminSidebar;

class MediaSidebarExtender extends AbstractAdminSidebar
{

    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {

//        $menu->group('media', function (Group $group) {
//            $group->hideHeading(true);
//            $group->item(trans('backend::sidebar.media'), function (Item $item) {
//                $item->icon('fas fa-camera');
//                $item->weight(10);
//                $item->route('admin.media.index');
//                $item->authorize(
//                    $this->auth->hasAccess('admin.media.index')
//                );
//
//
//
//
//            });
//        });

        return $menu;

    }
}
