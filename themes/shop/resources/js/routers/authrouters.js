import Permission from './../components/auth/permission/index';
import PermissionForm from './../components/auth/permission/form';

import Role from './../components/auth/role/index';
import RoleForm from './../components/auth/role/form';

import UserList from './../components/auth/user/index';
import UserForm from './../components/auth/user/form';




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



];
