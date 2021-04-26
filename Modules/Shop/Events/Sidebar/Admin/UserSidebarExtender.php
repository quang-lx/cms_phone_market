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
	    $menu->group('info ', function (Group $group) {
		    $group->hideHeading(true);
		    $group->item(trans('ch::sidebar.information'), function (Item $item) {
			    $item->icon('fas fa-info-circle');
			    $item->weight(10);
			    $item->authorize(
				    $this->auth->hasAccess('shop.company.index')
			    );
			    $item->route('shop.company.index');

		    });


	    });
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

        $menu->group('shop management', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('ch::sidebar.product management'), function (Item $item) {
                $item->icon('fa fa-shopping-cart');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('shop.product.index')
                );
                $item->item(trans('ch::sidebar.product management'), function (Item $item) {

                    $item->weight(0);

                    $item->route('shop.product.index');
                    $item->authorize(
                        $this->auth->hasAccess('shop.product.index')
                    );
                });
	            $item->item(trans('ch::sidebar.attribute'), function (Item $item) {

		            $item->weight(0);

		            $item->route('shop.attribute.index');
		            $item->authorize(
			            $this->auth->hasAccess('shop.attribute.index')
		            );
	            });


            });


        });

        $menu->group('shop management', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('ch::sidebar.voucher management'), function (Item $item) {
                $item->icon('fa fa-gift');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('shop.voucher.index')
                );
	            $item->route('shop.voucher.index');

            });


        });


        return $menu;

    }
}
