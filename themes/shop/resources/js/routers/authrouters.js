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

];
