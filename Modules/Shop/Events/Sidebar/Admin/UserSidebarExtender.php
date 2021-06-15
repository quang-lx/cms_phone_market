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
                $item->icon('el-icon-goods');
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

	            $item->item(trans('ch::sidebar.product information'), function (Item $item) {

		            $item->weight(0);

		            $item->route('shop.pinformation.index');
		            $item->authorize(
			            $this->auth->hasAccess('shop.pinformation.index')
		            );
	            });
            });


        });

        $menu->group('shop management', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('ch::sidebar.voucher management'), function (Item $item) {
                $item->icon('fa fa-gift');
                $item->weight(10);
	            $item->route('shop.voucher.index');
	            $item->authorize(
		            $this->auth->hasAccess('shop.voucher.index')
	            );

                $item->item(trans('ch::sidebar.voucher management'), function (Item $item) {

				    $item->weight(0);

				    $item->route('shop.voucher.index');
				    $item->authorize(
					    $this->auth->hasAccess('shop.voucher.index')
				    );
			    });

            });


        });
	    $menu->group('vt management', function (Group $group) {
		    $group->hideHeading(true);
		    $group->item(trans('ch::sidebar.vat tu'), function (Item $item) {
			    $item->icon('fas fa-tools');
			    $item->weight(10);
			    $item->authorize(
				    $this->auth->hasAccess('shop.vtcategory.index')
			    );

			    $item->item(trans('ch::sidebar.vtcategory'), function (Item $item) {

				    $item->weight(0);

				    $item->route('shop.vtcategory.index');
				    $item->authorize(
					    $this->auth->hasAccess('shop.vtcategory.index')
				    );
			    });
			    $item->item(trans('ch::sidebar.vtproduct'), function (Item $item) {

				    $item->weight(0);

				    $item->route('shop.vtproduct.index');
				    $item->authorize(
					    $this->auth->hasAccess('shop.vtproduct.index')
				    );
			    });
			    $item->item(trans('ch::sidebar.vtshopproduct'), function (Item $item) {

				    $item->weight(0);

				    $item->route('shop.vtshopproduct.create');
				    $item->authorize(
					    $this->auth->hasAccess('shop.vtshopproduct.create')
				    );
			    });
			    $item->item(trans('ch::sidebar.vtimportexcel'), function (Item $item) {

				    $item->weight(0);

				    $item->route('shop.vtimportexcel.index');
				    $item->authorize(
					    $this->auth->hasAccess('shop.vtimportexcel.index')
				    );
			    });

		    });


	    });

        $menu->group('transfer management', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('ch::sidebar.transfer management'), function (Item $item) {
                $item->icon('fa fa-truck');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('shop.transfer.index')
                );

                $item->item(trans('ch::sidebar.transfer management'), function (Item $item) {

                    $item->weight(0);

                    $item->route('shop.transfer.index');
                    $item->authorize(
                        $this->auth->hasAccess('shop.transfer.index')
                    );
                });

            });


        });

        $menu->group('storage product management', function (Group $group) {
            $group->hideHeading(true);
            $group->item(trans('ch::sidebar.storage product'), function (Item $item) {
                $item->icon('fas fa-save');
                $item->weight(10);
                $item->authorize(
                    $this->auth->hasAccess('shop.storageproduct.index')
                );

                $item->item(trans('ch::sidebar.storage product'), function (Item $item) {

                    $item->weight(0);

                    $item->route('shop.storageproduct.index');
                    $item->authorize(
                        $this->auth->hasAccess('shop.storageproduct.index')
                    );
                });

            });


        });

	    $menu->group('ship type', function (Group $group) {
		    $group->hideHeading(true);
		    $group->item(trans('ch::sidebar.ship type'), function (Item $item) {
			    $item->icon('fas fa-truck');
			    $item->weight(10);
			    $item->authorize(
				    $this->auth->hasAccess('shop.shopshiptype.index')
			    );

			    $item->route('shop.shopshiptype.index');

		    });


	    });

	    $menu->group('order management', function (Group $group) {
		    $group->hideHeading(true);
		    $group->item(trans('ch::sidebar.order'), function (Item $item) {
			    $item->icon('fas fa-save');
			    $item->weight(10);
			    $item->authorize(
				    $this->auth->hasAccess('shop.orders.index')
			    );

			    $item->item(trans('ch::sidebar.order sua chua'), function (Item $item) {

				    $item->weight(0);

				    $item->route('shop.orders.index');
				    $item->authorize(
					    $this->auth->hasAccess('shop.orders.index')
				    );
			    });
			    $item->item(trans('ch::sidebar.order bao hanh'), function (Item $item) {

				    $item->weight(0);

				    $item->route('shop.ordersguarantee.index');
				    $item->authorize(
					    $this->auth->hasAccess('shop.ordersguarantee.index')
				    );
			    });
			    $item->item(trans('ch::sidebar.order mua hang'), function (Item $item) {

				    $item->weight(0);

				    $item->route('shop.ordersbuysell.index');
				    $item->authorize(
					    $this->auth->hasAccess('shop.ordersbuysell.index')
				    );
			    });
		    });


	    });
        return $menu;

    }
}
