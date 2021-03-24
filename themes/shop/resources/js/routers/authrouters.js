import Permission from './../components/auth/permission/index';
import PermissionForm from './../components/auth/permission/form';

import Role from './../components/auth/role/index';
import RoleForm from './../components/auth/role/form';

import UserList from './../components/auth/user/index';
import UserForm from './../components/auth/user/form';

import AdminList from './../components/auth/admin/index';
import AdminForm from './../components/auth/admin/form';


import CategoryList from './../components/category/index';
import CategoryForm from './../components/category/form';


import NewsList from './../components/news/index';
import NewsForm from './../components/news/form';



const currentLocale = '/' + window.MonCMS.currentLocale;

export default [
    {
        path: '/admin/auth/permissions',
        name: 'admin.permissions.index',
        component: Permission,
    },
    {
        path: '/admin/auth/permissions/create',
        name: 'admin.permissions.create',
        component: PermissionForm,
        props: {
            pageTitle: 'permission.label.create_permission',
        },
    },

    {
        path: '/admin/auth/permissions/:permissionId/edit',
        name: 'admin.permissions.edit',
        component: PermissionForm,
        props: {
            pageTitle: 'permission.label.update_permission',
        },
    },
    // role
    {
        path: '/admin/auth/roles',
        name: 'admin.roles.index',
        component: Role,
    },
    {
        path: '/admin/auth/roles/create',
        name: 'admin.roles.create',
        component: RoleForm,
        props: {
            pageTitle: 'role.label.create_role',
        },
    },

    {
        path: '/admin/auth/roles/:roleId/edit',
        name: 'admin.roles.edit',
        component: RoleForm,
        props: {
            pageTitle: 'role.label.update_role',
        },
    },


    {
        path: '/admin/auth/quan-tri',
        name: 'admin.admins.index',
        component: AdminList,
    },
    {
        path: '/admin/auth/quan-tri/create',
        name: 'admin.admins.create',
        component: AdminForm,
        props: {
            pageTitle: 'user.label.create_admin',
        },
    },

    {
        path: '/admin/auth/quan-tri/:userId/edit',
        name: 'admin.admins.edit',
        component: AdminForm,
        props: {
            pageTitle: 'user.label.update_admin',
        },
    },


    {
        path: '/admin/danh-muc',
        name: 'admin.category.index',
        component: CategoryList,
    },
    {
        path: '/admin/danh-muc/create',
        name: 'admin.category.create',
        component: CategoryForm,
        props: {
            pageTitle: 'category.label.create_category',
        },
    },

    {
        path: '/admin/danh-muc/:categoryId/edit',
        name: 'admin.category.edit',
        component: CategoryForm,
        props: {
            pageTitle: 'category.label.update_category',
        },
    },

    {
        path: '/admin/tin-tuc',
        name: 'admin.news.index',
        component: NewsList,
    },
    {
        path: '/admin/tin-tuc/create',
        name: 'admin.news.create',
        component: NewsForm,
        props: {
            pageTitle: 'news.label.create_news',
        },
    },

    {
        path: '/admin/tin-tuc/:newsId/edit',
        name: 'admin.news.edit',
        component: NewsForm,
        props: {
            pageTitle: 'news.label.update_news',
        },
    },


];