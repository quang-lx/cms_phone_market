<?php

namespace Modules\Shop\Events\Sidebar\Admin;


use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Shop\Sidebar\AbstractAdminSidebar;

class UserSidebarExtender extends AbstractAdminSidebar
{

    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {

        $menu->group('shop system administration', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('backend::sidebar.system administration'), function (Item $item) {
                $item->icon('fas fa-lock');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('shop.roles.index')
                );

                $item->item(trans('backend::sidebar.roles'), function (Item $item) {

                    $item->weight(0);

                    $item->route('shop.roles.index');
                    $item->authorize(
                        $this->auth->hasAccess('shop.roles.index')
                    );
                });


            });


        });

        $menu->group('shop management', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('ch::sidebar.shop management'), function (Item $item) {
                $item->icon('fas fa-store');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('shop.shop.index')
                );
                $item->item(trans('ch::sidebar.shop management'), function (Item $item) {

                    $item->weight(0);

                    $item->route('shop.shop.index');
                    $item->authorize(
                        $this->auth->hasAccess('shop.shop.index')
                    );
                });


            });


        });

        $menu->group('shop management', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('ch::sidebar.user management'), function (Item $item) {
                $item->icon('fas fa-user');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('shop.user.index')
                );
                $item->item(trans('ch::sidebar.user management'), function (Item $item) {

                    $item->weight(0);

                    $item->route('shop.user.index');
                    $item->authorize(
                        $this->auth->hasAccess('shop.user.index')
                    );
                });


            });


        });


        return $menu;

    }
}
