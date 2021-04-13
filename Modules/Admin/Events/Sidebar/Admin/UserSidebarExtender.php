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

        $menu->group('product shop', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('backend::sidebar.product management'), function (Item $item) {
                $item->icon('fas fa-list-alt');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('admin.pcategory.index')
                );

                $item->item(trans('backend::sidebar.pcategory'), function (Item $item) {

                    $item->weight(0);

                    $item->route('admin.pcategory.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.pcategory.index')
                    );
                });
                $item->item(trans('backend::sidebar.brand'), function (Item $item) {

                    $item->weight(0);

                    $item->route('admin.brand.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.brand.index')
                    );
                });
                $item->item(trans('backend::sidebar.problem'), function (Item $item) {

                    $item->weight(0);

                    $item->route('admin.problem.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.problem.index')
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
                    $this->auth->hasAccess('admin.company.index')
                );
//                $item->item(trans('backend::sidebar.shop_permissions'), function (Item $item) {
//
//                    $item->weight(0);
//
//                    $item->route('admin.shoppermissions.shopindex');
//                    $item->authorize(
//                        $this->auth->hasAccess('admin.shoppermissions.shopindex')
//                    );
//                });

                $item->item(trans('backend::sidebar.company'), function (Item $item) {

                    $item->weight(0);
                    $item->isActiveWhen('/admin/company');
                    $item->route('admin.company.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.company.index')
                    );
                });

                $item->item(trans('backend::sidebar.company info'), function (Item $item) {

                    $item->weight(0);
                    $item->isActiveWhen('/admin/company/danh-sach');
                    $item->route('admin.company.index1');
                    $item->authorize(
                        $this->auth->hasAccess('admin.company.index1')
                    );
                });

                $item->item(trans('backend::sidebar.company priority'), function (Item $item) {

                    $item->weight(0);
                    $item->isActiveWhen('/admin/company/uu-tien');
                    $item->route('admin.company.priority');
                    $item->authorize(
                        $this->auth->hasAccess('admin.company.priority')
                    );
                });
            });


        });

        $menu->group('news', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('backend::sidebar.news'), function (Item $item) {
                $item->icon('fas fa-newspaper');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('admin.news.index')
                );

                $item->item(trans('backend::sidebar.category'), function (Item $item) {

                    $item->weight(0);

                    $item->route('admin.category.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.category.index')
                    );
                });
                $item->item(trans('backend::sidebar.news'), function (Item $item) {

                    $item->weight(0);

                    $item->route('admin.news.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.news.index')
                    );
                });

            });
        });
        $menu->group('setting', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('backend::sidebar.setting'), function (Item $item) {
                $item->icon('fas fa-cog');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('admin.homesetting.index')
                );

                $item->item(trans('backend::sidebar.homesetting'), function (Item $item) {

                    $item->weight(0);

                    $item->route('admin.homesetting.index');
                    $item->authorize(
                        $this->auth->hasAccess('admin.homesetting.index')
                    );
                });


            });
        });

        return $menu;

    }
}
