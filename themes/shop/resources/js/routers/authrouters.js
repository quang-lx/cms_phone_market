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

import AttributeForm from './../components/attribute/form';
import AttributeList from './../components/attribute/index';

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
            pageTitle: 'attribute.label.update_title',
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

];
