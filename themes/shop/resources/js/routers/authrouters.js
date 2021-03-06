import Permission from './../components/auth/permission/index';
import PermissionForm from './../components/auth/permission/form';

import Role from './../components/auth/role/index';
import RoleForm from './../components/auth/role/form';

import UserList from './../components/auth/user/index';
import UserForm from './../components/auth/user/form';

import ShopList from './../components/shop/index';
import ShopForm from './../components/shop/form';

import UserList2 from './../components/user/index';
import UserForm2 from './../components/user/form';

import ProductList from './../components/product/index';
import ProductForm from './../components/product/form';

import CompanyList from './../components/company/index';
import CompanyForm from './../components/company/form';

import VoucherList from './../components/voucher/index';
import VoucherForm from './../components/voucher/form';

import AttributeForm from './../components/attribute/form';
import AttributeList from './../components/attribute/index';

import PInformationForm from './../components/pinformation/form';
import PInformationList from './../components/pinformation/index';

import VtCategoryForm from './../components/vtcategory/form';
import VtCategoryList from './../components/vtcategory/index';

import VtProductForm from './../components/vtproduct/form';
import VtProductList from './../components/vtproduct/index';

import TransferForm from './../components/transfer/form';
import TransferList from './../components/transfer/index';
import VtImportExcelDetail from './../components/vtimportexcel/detail';
import VtImportExcelList from './../components/vtimportexcel/index';
import VtImportExcelCreate from './../components/vtimportexcel/create';

import StorageProductForm from './../components/storageproduct/form';
import StorageProductList from './../components/storageproduct/index';

import ShopShipTypeList from './../components/shopshiptype/index';

import OrderDetail from './../components/orders/detail';
import OrderList from './../components/orders/index';

import DashboardList from './../components/dashboard/index';
import OrderGuaranteeList from './../components/orders/index_guarantee';
import OrderBuySellList from './../components/orders/index_buysell';
import OrderBuySellDetail from './../components/orders/detail_buysell';
import OrderGuaranteeDetail  from './../components/orders/detail_guarantee';
import OrderBuySellForm from './../components/orders/form_buysell';
import OrderRepairForm from './../components/orders/form_repair';

import VtShopProductForm from './../components/vtshopproduct/form';
import VtShopProductList from './../components/vtshopproduct/index';

import ShopCategoryList from './../components/shopcategory/index';


const currentLocale = '/' + window.MonCMS.currentLocale;

export default [


    // role
    {
        path: '/shop-admin/auth/roles',
        name: 'shop.roles.index',
        component: Role,
    },
    {
        path: '/shop-admin/auth/roles/create',
        name: 'shop.roles.create',
        component: RoleForm,
        props: {
            pageTitle: 'role.label.create_role',
        },
    },

    {
        path: '/shop-admin/auth/roles/:roleId/edit',
        name: 'shop.roles.edit',
        component: RoleForm,
        props: {
            pageTitle: 'role.label.update_role',
        },
    },

    // shop
    {
        path: '/shop-admin/shop',
        name: 'shop.shop.index',
        component: ShopList,
    },
    {
        path: '/shop-admin/shop/create',
        name: 'shop.shop.create',
        component: ShopForm,
        props: {
            pageTitle: 'shop.label.create_shop',
        },
    },

    {
        path: '/shop-admin/shop/:shopId/edit',
        name: 'shop.shop.edit',
        component: ShopForm,
        props: {
            pageTitle: 'shop.label.update_shop',
        },
    },

    // user
    {
        path: '/shop-admin/user',
        name: 'shop.user.index',
        component: UserList2,
    },
    {
        path: '/shop-admin/user/create',
        name: 'shop.user.create',
        component: UserForm2,
        props: {
            pageTitle: 'user.label.create_user',
        },
    },

    {
        path: '/shop-admin/user/:userId/edit',
        name: 'shop.user.edit',
        component: UserForm2,
        props: {
            pageTitle: 'user.label.update_user',
        },
    },

    // product
    {
        path: '/shop-admin/product',
        name: 'shop.product.index',
        component: ProductList,
    },
    {
        path: '/shop-admin/product/create',
        name: 'shop.product.create',
        component: ProductForm,
        props: {
            pageTitle: 'product.label.create_product',
        },
    },

    {
        path: '/shop-admin/product/:productId/edit',
        name: 'shop.product.edit',
        component: ProductForm,
        props: {
            pageTitle: 'product.label.update_product',
        },
    },

    // company
    {
        path: '/shop-admin/company',
        name: 'shop.company.index',
        component: CompanyList,
    },
    {
        path: '/shop-admin/company/edit',
        name: 'shop.company.edit',
        component: CompanyForm,
        props: {
            pageTitle: 'company.label.update_title',
        },
    },

    // voucher
    {
        path: '/shop-admin/voucher',
        name: 'shop.voucher.index',
        component: VoucherList,
    },
    {
        path: '/shop-admin/voucher/create',
        name: 'shop.voucher.create',
        component: VoucherForm,
        props: {
            pageTitle: 'voucher.label.create_voucher',
        },
    },
	{
        path: '/shop-admin/voucher/:voucherId/edit',
        name: 'shop.voucher.edit',
        component: VoucherForm,
        props: {
            pageTitle: 'voucher.label.update_voucher',
        },
    },

   {
        path: '/shop-admin/attribute',
        name: 'shop.attribute.index',
        component: AttributeList,
    },
    {
        path: '/shop-admin/attribute/create',
        name: 'shop.attribute.create',
        component: AttributeForm,
        props: {
            pageTitle: 'attribute.label.create_title',
        },
    },

    {
        path: '/shop-admin/attribute/:attributeId/edit',
        name: 'shop.attribute.edit',
        component: AttributeForm,
        props: {
            pageTitle: 'attribute.label.update_title',
        },
    },

      //
    {
        path: '/shop-admin/pinformation',
        name: 'shop.pinformation.index',
        component: PInformationList,
    },
    {
        path: '/shop-admin/pinformation/create',
        name: 'shop.pinformation.create',
        component: PInformationForm,
        props: {
            pageTitle: 'pinformation.label.create_title',
        },
    },

    {
        path: '/shop-admin/pinformation/:pinformationId/edit',
        name: 'shop.pinformation.edit',
        component: PInformationForm,
        props: {
            pageTitle: 'pinformation.label.update_title',
        },
    },

       //
    {
        path: '/shop-admin/vtcategory',
        name: 'shop.vtcategory.index',
        component: VtCategoryList,
    },
    {
        path: '/shop-admin/vtcategory/create',
        name: 'shop.vtcategory.create',
        component: VtCategoryForm,
        props: {
            pageTitle: 'vtcategory.label.create_title',
        },
    },

    {
        path: '/shop-admin/vtcategory/:vtcategoryId/edit',
        name: 'shop.vtcategory.edit',
        component: VtCategoryForm,
        props: {
            pageTitle: 'vtcategory.label.update_title',
        },
    },

     //
    {
        path: '/shop-admin/vtproduct',
        name: 'shop.vtproduct.index',
        component: VtProductList,
    },
    {
        path: '/shop-admin/vtproduct/create',
        name: 'shop.vtproduct.create',
        component: VtProductForm,
        props: {
            pageTitle: 'vtproduct.label.create_title',
        },
    },

    {
        path: '/shop-admin/vtproduct/:vtproductId/edit',
        name: 'shop.vtproduct.edit',
        component: VtProductForm,
        props: {
            pageTitle: 'vtproduct.label.update_title',
        },
    },
    // transfer-history
    {
        path: '/shop-admin/transfer',
        name: 'shop.transfer.index',
        component: TransferList,
    },
    {
        path: '/shop-admin/transfer/create',
        name: 'shop.transfer.create',
        component: TransferForm,
        props: {
            pageTitle: 'transfer.label.create_transfer',
        },
    },
	{
        path: '/shop-admin/transfer/:transferId/edit',
        name: 'shop.transfer.edit',
        component: TransferForm,
        props: {
            pageTitle: 'transfer.label.update_transfer',
        },
    },

     //vtimportexcel
    {
        path: '/shop-admin/vtimportexcel',
        name: 'shop.vtimportexcel.index',
        component: VtImportExcelList,
    },
    {
        path: '/shop-admin/vtimportexcel/create',
        name: 'shop.vtimportexcel.create',
        component: VtImportExcelCreate,
        props: {
            pageTitle: 'vtimportexcel.label.create_title',
        },
    },

    {
        path: '/shop-admin/vtimportexcel/:vtimportexcelId/detail',
        name: 'shop.vtimportexcel.detail',
        component: VtImportExcelDetail,
        props: {
            pageTitle: 'vtimportexcel.label.detail_title',
        },
    },

    // storage product
    {
        path: '/shop-admin/storageproduct',
        name: 'shop.storageproduct.index',
        component: StorageProductList,
    },
	{
        path: '/shop-admin/storageproduct/:storageproductId/edit',
        name: 'shop.storageproduct.edit',
        component: StorageProductForm,
        props: {
            pageTitle: 'storageproduct.label.update_storageproduct',
        },
    },

    // shopshiptype
    {
        path: '/shop-admin/shopshiptype',
        name: 'shop.shopshiptype.index',
        component: ShopShipTypeList,
    },

     //order
     {
        path: '/shop-admin/orders',
        name: 'shop.orders.index',
        component: OrderList,
    },

    {
        path: '/shop-admin/orders/:ordersId/detail',
        name: 'shop.orders.detail',
        component: OrderDetail,
        props: {
            pageTitle: 'orders.label.detail_title',
        },
    },

    {
        path: '/shop-admin/orders/guarantee',
        name: 'shop.ordersguarantee.index',
        component: OrderGuaranteeList,
    },
    
    {
        path: '/shop-admin/orders/buysell',
        name: 'shop.ordersbuysell.index',
        component: OrderBuySellList,
    },

    {
        path: '/shop-admin/orders/buysell/create',
        name: 'shop.ordersbuysell.create',
        component: OrderBuySellForm,
        props: {
            pageTitle: 'orders.label.create',
        },
    },

    {
        path: '/shop-admin/orders/create',
        name: 'shop.ordersrepair.create',
        component: OrderRepairForm,
        props: {
            pageTitle: 'orders.label.create',
        },
    },

    {
        path: '/shop-admin/orders/buysell/{orderId}/update',
        name: 'shop.ordersbuysell.update',
        component: OrderBuySellForm,
        props: {
            pageTitle: 'orders.label.update',
        },
    },

    {
        path: '/shop-admin/orders/:ordersId/detail-buysell',
        name: 'shop.orders.detailbuysell',
        component: OrderBuySellDetail,
        props: {
            pageTitle: 'orders.label.detail_title',
        },
    },

    {
        path: '/shop-admin/orders/:ordersId/detail-guarantee',
        name: 'shop.orders.detailguarantee',
        component: OrderGuaranteeDetail,
        props: {
            pageTitle: 'orders.label.detail_title',
        },
    },

    //dashboard
    {
        path: '/shop-admin',
        name: 'shop.dashboard.index',
        component: DashboardList,
    },
    

    // shopcategory
    {
        path: '/shop-admin/shopcategory',
        name: 'shop.shopcategory.index',
        component: ShopCategoryList,
    },

    // vt-shop-product
    {
        path: '/shop-admin/vtshopproduct',
        name: 'shop.vtshopproduct.index',
        component: VtShopProductList,
    },
    {
        path: '/shop-admin/vtshopproduct/create',
        name: 'shop.vtshopproduct.create',
        component: VtShopProductForm,
        props: {
            pageTitle: 'vtshopproduct.label.create_title',
        },
    },

];
