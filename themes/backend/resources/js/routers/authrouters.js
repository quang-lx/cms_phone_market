import ShopPermission from './../components/auth/permission/shop/index';
import Permission from './../components/auth/permission/index';
import PermissionForm from './../components/auth/permission/form';

import Role from './../components/auth/role/index';
import RoleForm from './../components/auth/role/form';


import AdminList from './../components/auth/admin/index';
import AdminForm from './../components/auth/admin/form';


import CategoryList from './../components/category/index';
import CategoryForm from './../components/category/form';


import NewsList from './../components/news/index';
import NewsForm from './../components/news/form';

import ShopRole from './../components/auth/role/shop/index';
import ShopRoleForm from './../components/auth/role/shop/form';

import ProvinceList from './../components/province/index';
import ProvinceForm from './../components/province/form';

import DistrictList from './../components/district/index';
import DistrictForm from './../components/district/form';

import PhoenixList from './../components/phoenix/index';
import PhoenixForm from './../components/phoenix/form';

import CompanyList from './../components/company/index';
import CompanyList1 from './../components/company/index1';
import CompanyForm from './../components/company/form';
import CompanyDetail from './../components/company/DetailCompany/form';
import CompanyPriority from './../components/company/priority';

import PcategoryList from './../components/pcategory/index';
import PcategoryForm from './../components/pcategory/form';

import BrandList from './../components/brand/index';
import BrandForm from './../components/brand/form';

import AccountList from './../components/account/index';
import AccountDetail from './../components/account/detail';


import ProblemList from './../components/problem/index';
import ProblemForm from './../components/problem/form';

import HomeSettingList from './../components/homesetting/index';
import BannersList from './../components/banners/index';
import BannersForm from './../components/banners/form';

import AttributeForm from './../components/attribute/form';
import AttributeList from './../components/attribute/index';

import ProductDetail from './../components/product/detail';
import ProductList from './../components/product/index';

import PInformationForm from './../components/pinformation/form';
import PInformationList from './../components/pinformation/index';

import VoucherList from './../components/voucher/index';
import VoucherForm from './../components/voucher/form';

import RankList from './../components/rank/index';
import RankForm from './../components/rank/form';

const currentLocale = '/' + window.MonCMS.currentLocale;

export default [
    {
        path: '/admin/auth/shop-permissions',
        name: 'admin.shoppermissions.shopindex',
        component: ShopPermission,
    },
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


    // role
    {
        path: '/admin/auth/shop-roles',
        name: 'admin.shoproles.index',
        component: ShopRole,
    },
    {
        path: '/admin/auth/shop-roles/create',
        name: 'admin.shoproles.create',
        component: ShopRoleForm,
        props: {
            pageTitle: 'role.label.create_shoprole',
        },
    },

    {
        path: '/admin/auth/shop-roles/:roleId/edit',
        name: 'admin.shoproles.edit',
        component: ShopRoleForm,
        props: {
            pageTitle: 'role.label.update_shoprole',
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

    //
    {
        path: '/admin/province',
        name: 'admin.province.index',
        component: ProvinceList,
    },
    {
        path: '/admin/province/create',
        name: 'admin.province.create',
        component: ProvinceForm,
        props: {
            pageTitle: 'province.label.create_province',
        },
    },

    {
        path: '/admin/province/:provinceId/edit',
        name: 'admin.province.edit',
        component: ProvinceForm,
        props: {
            pageTitle: 'province.label.update_province',
        },
    },

    //

    {
        path: '/admin/district',
        name: 'admin.district.index',
        component: DistrictList,
    },
    {
        path: '/admin/district/create',
        name: 'admin.district.create',
        component: DistrictForm,
        props: {
            pageTitle: 'district.label.create_district',
        },
    },

    {
        path: '/admin/district/:districtId/edit',
        name: 'admin.district.edit',
        component: DistrictForm,
        props: {
            pageTitle: 'district.label.update_district',
        },
    },
    //
    {
        path: '/admin/phoenix',
        name: 'admin.phoenix.index',
        component: PhoenixList,
    },
    {
        path: '/admin/phoenix/create',
        name: 'admin.phoenix.create',
        component: PhoenixForm,
        props: {
            pageTitle: 'phoenix.label.create_phoenix',
        },
    },

    {
        path: '/admin/phoenix/:phoenixId/edit',
        name: 'admin.phoenix.edit',
        component: PhoenixForm,
        props: {
            pageTitle: 'phoenix.label.update_phoenix',
        },
    },


//
    {
        path: '/admin/company',
        name: 'admin.company.index',
        component: CompanyList,
    },

    {
        path: '/admin/company/danh-sach',
        name: 'admin.company.index1',
        component: CompanyList1,
    },

    {
        path: '/admin/company/create',
        name: 'admin.company.create',
        component: CompanyForm,
        props: {
            pageTitle: 'company.label.create_title',
        },
    },

    {
        path: '/admin/company/:companyId/edit',
        name: 'admin.company.edit',
        component: CompanyForm,
        props: {
            pageTitle: 'company.label.update_title',
        },
    },

    {
        path: '/admin/company/:companyId/chi-tiet',
        name: 'admin.company.detail',
        component: CompanyDetail,
        props: {
            pageTitle: 'company.label.detail_title',
        },
    },

    {
        path: '/admin/company/uu-tien',
        name: 'admin.company.priority',
        component: CompanyPriority,
        props: {
            pageTitle: 'company.label.priority_title',
        },
    },

    //
    {
        path: '/admin/pcategory',
        name: 'admin.pcategory.index',
        component: PcategoryList,
    },
    {
        path: '/admin/pcategory/create',
        name: 'admin.pcategory.create',
        component: PcategoryForm,
        props: {
            pageTitle: 'pcategory.label.create_pcategory',
        },
    },

    {
        path: '/admin/pcategory/:pcategoryId/edit',
        name: 'admin.pcategory.edit',
        component: PcategoryForm,
        props: {
            pageTitle: 'pcategory.label.update_pcategory',
        },
    },

    //
    {
        path: '/admin/brand',
        name: 'admin.brand.index',
        component: BrandList,
    },
    {
        path: '/admin/brand/create',
        name: 'admin.brand.create',
        component: BrandForm,
        props: {
            pageTitle: 'brand.label.create_title',
        },
    },

    {
        path: '/admin/brand/:brandId/edit',
        name: 'admin.brand.edit',
        component: BrandForm,
        props: {
            pageTitle: 'brand.label.update_title',
        },
    },

     //
    {
        path: '/admin/account',
        name: 'admin.account.index',
        component: AccountList,
    },

    {
        path: '/admin/account/:accountId/detail',
        name: 'admin.account.detail',
        component: AccountDetail,
        props: {
            pageTitle: 'account.label.detail_account',
        },
    },


    //
    {
        path: '/admin/problem',
        name: 'admin.problem.index',
        component: ProblemList,
    },
    {
        path: '/admin/problem/create',
        name: 'admin.problem.create',
        component: ProblemForm,
        props: {
            pageTitle: 'problem.label.update_item',
        },
    },

    {
        path: '/admin/problem/:problemId/edit',
        name: 'admin.problem.edit',
        component: ProblemForm,
        props: {
            pageTitle: 'problem.label.update_item',
        },
    },
 

    //
    {
        path: '/admin/banners',
        name: 'admin.banners.index',
        component: BannersList,
    },
    {
        path: '/admin/banners/create',
        name: 'admin.banners.create',
        component: BannersForm,
        props: {
            pageTitle: 'banners.label.update_title',
        },
    },

    {
        path: '/admin/banners/:bannersId/edit',
        name: 'admin.banners.edit',
        component: BannersForm,
        props: {
            pageTitle: 'banners.label.update_title',
        },
    },
 
  //
    {
        path: '/admin/homesetting',
        name: 'admin.homesetting.index',
        component: HomeSettingList,
    },

    //
    {
        path: '/admin/attribute',
        name: 'admin.attribute.index',
        component: AttributeList,
    },
    {
        path: '/admin/attribute/create',
        name: 'admin.attribute.create',
        component: AttributeForm,
        props: {
            pageTitle: 'attribute.label.create_title',
        },
    },

    {
        path: '/admin/attribute/:attributeId/edit',
        name: 'admin.attribute.edit',
        component: AttributeForm,
        props: {
            pageTitle: 'attribute.label.update_title',
        },
    },
    //
    {
        path: '/admin/product',
        name: 'admin.product.index',
        component: ProductList,
    },

    {
        path: '/admin/product/:productId/detail',
        name: 'admin.product.detail',
        component: ProductDetail,
        props: {
            pageTitle: 'product.label.detail_product',
        },
    },
    //
    {
        path: '/admin/pinformation',
        name: 'admin.pinformation.index',
        component: PInformationList,
    },
    {
        path: '/admin/pinformation/create',
        name: 'admin.pinformation.create',
        component: PInformationForm,
        props: {
            pageTitle: 'pinformation.label.create_title',
        },
    },

    {
        path: '/admin/pinformation/:pinformationId/edit',
        name: 'admin.pinformation.edit',
        component: PInformationForm,
        props: {
            pageTitle: 'pinformation.label.update_title',
        },
    },

    // voucher
    {
        path: '/admin/voucher',
        name: 'admin.voucher.index',
        component: VoucherList,
    },
    {
        path: '/admin/voucher/create',
        name: 'admin.voucher.create',
        component: VoucherForm,
        props: {
            pageTitle: 'voucher.label.create_voucher',
        },
    },
    {
        path: '/admin/voucher/:voucherId/edit',
        name: 'admin.voucher.edit',
        component: VoucherForm,
        props: {
            pageTitle: 'voucher.label.update_voucher',
        },
    },

    // rank
    {
        path: '/admin/rank',
        name: 'admin.rank.index',
        component: RankList,
    },
    {
        path: '/admin/rank/create',
        name: 'admin.rank.create',
        component: RankForm,
        props: {
            pageTitle: 'rank.label.create_rank',
        },
    },
    {
        path: '/admin/rank/:rankId/edit',
        name: 'admin.rank.edit',
        component: RankForm,
        props: {
            pageTitle: 'rank.label.update_rank',
        },
    },
 
];
