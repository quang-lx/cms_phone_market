<?php

namespace Modules\Admin\Events\Sidebar\Admin;


use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Admin\Sidebar\AbstractAdminSidebar;

class UserSidebarExtender extends AbstractAdminSidebar
{

    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {

        $menu->group('system administration', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('backend::sidebar.system administration'), function (Item $item) {
                $item->icon('fas fa-lock');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('admin.admins.index')
                );

                $item->item(trans('backend::sidebar.roles'), function (Item $item) {

                    $item->weight(0);

                    $item->route('admin.roles.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.roles.index')
                    );
                });

                $item->item(trans('backend::sidebar.admins'), function (Item $item) {

                    $item->weight(0);

                    $item->route('admin.admins.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.admins.index')
                    );
                });




            });


        });
        $menu->group('system shop', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('backend::sidebar.system shop'), function (Item $item) {
                $item->icon('fas fa-store');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('admin.shoppermissions.shopindex')
                );
                $item->item(trans('backend::sidebar.shop_permissions'), function (Item $item) {

                    $item->weight(0);

                    $item->route('admin.shoppermissions.shopindex');
                    $item->authorize(
                        $this->auth->hasAccess('admin.shoppermissions.shopindex')
                    );
                });





            });


        });


        return $menu;

    }
}
