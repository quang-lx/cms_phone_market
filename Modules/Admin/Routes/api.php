<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/admin', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->prefix('auth')->group(function (){
	Route::get('/permissions/all-by-group', [
		'as' => 'api.permissions.all-by-group',
		'uses' => 'Auth\PermissionController@allByGroup',

	]);
    Route::get('/permissions', [
        'as' => 'api.permissions.index',
        'uses' => 'Auth\PermissionController@index',

    ]);
    Route::delete('permissions/{permission}', [
        'as' => 'api.permissions.destroy',
        'uses' => 'Auth\PermissionController@destroy',

    ]);
    Route::post('permissions', [
        'as' => 'api.permissions.store',
        'uses' => 'Auth\PermissionController@store',

    ]);
    Route::get('permissions/{permission}', [
        'as' => 'api.permissions.find',
        'uses' => 'Auth\PermissionController@find',

    ]);
    Route::post('permissions/{permission}/edit', [
        'as' => 'api.permissions.update',
        'uses' => 'Auth\PermissionController@update',

    ]);
    //roles

    Route::get('/roles/all', [
        'as' => 'api.roles.all',
        'uses' => 'Auth\RoleController@all',

    ]);
    Route::get('/roles', [
        'as' => 'api.roles.index',
        'uses' => 'Auth\RoleController@index',

    ]);
    Route::get('/roles', [
        'as' => 'api.roles.index',
        'uses' => 'Auth\RoleController@index',

    ]);
    Route::delete('roles/{role}', [
        'as' => 'api.roles.destroy',
        'uses' => 'Auth\RoleController@destroy',

    ]);
    Route::post('roles', [
        'as' => 'api.roles.store',
        'uses' => 'Auth\RoleController@store',

    ]);
	Route::get('roles/find-new', [
		'as' => 'api.roles.find-new',
		'uses' => 'Auth\RoleController@findNew',

	]);
    Route::get('roles/{role}', [
        'as' => 'api.roles.find',
        'uses' => 'Auth\RoleController@find',

    ]);


    Route::post('roles/{role}/edit', [
        'as' => 'api.roles.update',
        'uses' => 'Auth\RoleController@update',

    ]);
    Route::post('roles/{role}/assign-permission', [
        'as' => 'api.roles.assignPermissions',
        'uses' => 'Auth\RoleController@assignPermissions',

    ]);
    Route::post('roles/{role}/remove-permission', [
        'as' => 'api.roles.removePermissions',
        'uses' => 'Auth\RoleController@removePermissions',

    ]);
    // users
    Route::get('users', [
        'as' => 'api.users.index',
        'uses' => 'Auth\UserController@index',

    ]);
    Route::delete('users/{user}', [
        'as' => 'api.users.destroy',
        'uses' => 'Auth\UserController@destroy',

    ]);

    Route::get('users/has-permissions', [
        'as' => 'api.users.hasPermissions',
        'uses' => 'Auth\UserController@hasPermissions',
    ]);

    Route::get('users/{user}', [
        'as' => 'api.users.find',
        'uses' => 'Auth\UserController@find',
    ]);
    Route::post('users/{user}/edit', [
        'as' => 'api.users.update',
        'uses' => 'Auth\UserController@update',

    ]);
    Route::post('users', [
        'as' => 'api.users.store',
        'uses' => 'Auth\UserController@store',

    ]);

    Route::post('users/{user}/change-password', [
        'as' => 'api.users.change-password',
        'uses' => 'Auth\UserController@changePassword',

    ]);



});


Route::middleware('auth:api')->prefix('/danh-muc')->group(function (){
    // alarm level

    Route::get('/', [
        'as' => 'api.category.index',
        'uses' => 'Category\CategoryController@index',

    ]);
    Route::get('/tree', [
        'as' => 'api.category.tree',
        'uses' => 'Category\CategoryController@tree',

    ]);
    Route::delete('/{category}', [
        'as' => 'api.category.destroy',
        'uses' => 'Category\CategoryController@destroy',

    ]);

    Route::get('/{category}', [
        'as' => 'api.category.find',
        'uses' => 'Category\CategoryController@find',
    ]);
    Route::post('/{category}/edit', [
        'as' => 'api.category.update',
        'uses' => 'Category\CategoryController@update',

    ]);
    Route::post('/', [
        'as' => 'api.category.store',
        'uses' => 'Category\CategoryController@store',

    ]);
});


Route::middleware('auth:api')->prefix('/tin-tuc')->group(function (){
    // alarm level

    Route::get('/', [
        'as' => 'api.news.index',
        'uses' => 'News\NewsController@index',

    ]);
    Route::delete('/{news}', [
        'as' => 'api.news.destroy',
        'uses' => 'News\NewsController@destroy',

    ]);

    Route::get('/{news}', [
        'as' => 'api.news.find',
        'uses' => 'News\NewsController@find',
    ]);
    Route::post('/{news}/edit', [
        'as' => 'api.news.update',
        'uses' => 'News\NewsController@update',

    ]);
    Route::post('/', [
        'as' => 'api.news.store',
        'uses' => 'News\NewsController@store',

    ]);

});

Route::middleware('auth:api')->prefix('/provinces')->group(function (){

    Route::get('/', [
        'as' => 'api.province.index',
        'uses' => 'Province\ProvinceController@index',
    ]);
    Route::post('/{province}/edit', [
            'as' => 'api.province.update',
            'uses' => 'Province\ProvinceController@update',
        ]);
   Route::get('/{province}', [
              'as' => 'api.province.find',
              'uses' => 'Province\ProvinceController@find',
          ]);
    Route::post('/', [
        'as' => 'api.province.store',
        'uses' => 'Province\ProvinceController@store',
    ]);

    Route::delete('/{province}', [
        'as' => 'api.province.destroy',
        'uses' => 'Province\ProvinceController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/districts')->group(function (){

    Route::get('/', [
        'as' => 'api.district.index',
        'uses' => 'District\DistrictController@index',
    ]);
    Route::post('/{district}/edit', [
            'as' => 'api.district.update',
            'uses' => 'District\DistrictController@update',
        ]);
   Route::get('/{district}', [
              'as' => 'api.district.find',
              'uses' => 'District\DistrictController@find',
          ]);
    Route::post('/', [
        'as' => 'api.district.store',
        'uses' => 'District\DistrictController@store',
    ]);

    Route::delete('/{district}', [
        'as' => 'api.district.destroy',
        'uses' => 'District\DistrictController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/phoenixes')->group(function (){

    Route::get('/', [
        'as' => 'api.phoenix.index',
        'uses' => 'Phoenix\PhoenixController@index',
    ]);
    Route::post('/{phoenix}/edit', [
            'as' => 'api.phoenix.update',
            'uses' => 'Phoenix\PhoenixController@update',
        ]);
   Route::get('/{phoenix}', [
              'as' => 'api.phoenix.find',
              'uses' => 'Phoenix\PhoenixController@find',
          ]);
    Route::post('/', [
        'as' => 'api.phoenix.store',
        'uses' => 'Phoenix\PhoenixController@store',
    ]);

    Route::delete('/{phoenix}', [
        'as' => 'api.phoenix.destroy',
        'uses' => 'Phoenix\PhoenixController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/companies')->group(function (){

    Route::get('/', [
        'as' => 'api.company.index',
        'uses' => 'Company\CompanyController@index',
    ]);
    Route::get('danh-sach', [
        'as' => 'api.company.index1',
        'uses' => 'Company\CompanyController@index1',
    ]);
    Route::post('/{company}/edit', [
            'as' => 'api.company.update',
            'uses' => 'Company\CompanyController@update',
        ]);
   Route::get('/{company}', [
              'as' => 'api.company.find',
              'uses' => 'Company\CompanyController@find',
          ]);
    Route::post('/', [
        'as' => 'api.company.store',
        'uses' => 'Company\CompanyController@store',
    ]);

    Route::delete('/{company}', [
        'as' => 'api.company.destroy',
        'uses' => 'Company\CompanyController@destroy',
    ]);

    Route::get('{company}/chi-tiet', [
        'as' => 'api.company.findCompany',
        'uses' => 'Company\CompanyController@findCompany',
    ]);
});
Route::middleware('auth:api')->prefix('/pcategories')->group(function (){

    Route::get('/', [
        'as' => 'api.pcategory.index',
        'uses' => 'Pcategory\PcategoryController@index',
    ]);
    Route::post('/{pcategory}/edit', [
            'as' => 'api.pcategory.update',
            'uses' => 'Pcategory\PcategoryController@update',
        ]);
   Route::get('/{pcategory}', [
              'as' => 'api.pcategory.find',
              'uses' => 'Pcategory\PcategoryController@find',
          ]);
    Route::post('/', [
        'as' => 'api.pcategory.store',
        'uses' => 'Pcategory\PcategoryController@store',
    ]);

    Route::delete('/{pcategory}', [
        'as' => 'api.pcategory.destroy',
        'uses' => 'Pcategory\PcategoryController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/brands')->group(function (){

    Route::get('/', [
        'as' => 'api.brand.index',
        'uses' => 'Brand\BrandController@index',
    ]);
    Route::post('/{brand}/edit', [
            'as' => 'api.brand.update',
            'uses' => 'Brand\BrandController@update',
        ]);
   Route::get('/{brand}', [
              'as' => 'api.brand.find',
              'uses' => 'Brand\BrandController@find',
          ]);
    Route::post('/', [
        'as' => 'api.brand.store',
        'uses' => 'Brand\BrandController@store',
    ]);

    Route::delete('/{brand}', [
        'as' => 'api.brand.destroy',
        'uses' => 'Brand\BrandController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/accounts')->group(function (){

    Route::get('/', [
        'as' => 'api.account.index',
        'uses' => 'Account\AccountController@index',
    ]);
    Route::post('/{account}/edit', [
            'as' => 'api.account.update',
            'uses' => 'Account\AccountController@update',
        ]);
   Route::get('/{account}', [
              'as' => 'api.account.find',
              'uses' => 'Account\AccountController@find',
          ]);
    Route::post('/', [
        'as' => 'api.account.store',
        'uses' => 'Account\AccountController@store',
    ]);

    Route::delete('/{account}', [
        'as' => 'api.account.destroy',
        'uses' => 'Account\AccountController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/problems')->group(function (){

    Route::get('/', [
        'as' => 'api.problem.index',
        'uses' => 'Problem\ProblemController@index',
    ]);
    Route::post('/{problem}/edit', [
            'as' => 'api.problem.update',
            'uses' => 'Problem\ProblemController@update',
        ]);
   Route::get('/{problem}', [
              'as' => 'api.problem.find',
              'uses' => 'Problem\ProblemController@find',
          ]);
    Route::post('/', [
        'as' => 'api.problem.store',
        'uses' => 'Problem\ProblemController@store',
    ]);

    Route::delete('/{problem}', [
        'as' => 'api.problem.destroy',
        'uses' => 'Problem\ProblemController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/homesettings')->group(function (){

    Route::get('/', [
        'as' => 'api.homesetting.index',
        'uses' => 'HomeSetting\HomeSettingController@index',
    ]);
    Route::post('/{homesetting}/edit', [
            'as' => 'api.homesetting.update',
            'uses' => 'HomeSetting\HomeSettingController@update',
        ]);
   Route::get('/{homesetting}', [
              'as' => 'api.homesetting.find',
              'uses' => 'HomeSetting\HomeSettingController@find',
          ]);
    Route::post('/', [
        'as' => 'api.homesetting.store',
        'uses' => 'HomeSetting\HomeSettingController@store',
    ]);

    Route::delete('/{homesetting}', [
        'as' => 'api.homesetting.destroy',
        'uses' => 'HomeSetting\HomeSettingController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/banners')->group(function (){

    Route::get('/', [
        'as' => 'api.banners.index',
        'uses' => 'Banners\BannersController@index',
    ]);
    Route::post('/{banners}/edit', [
            'as' => 'api.banners.update',
            'uses' => 'Banners\BannersController@update',
        ]);
   Route::get('/{banners}', [
              'as' => 'api.banners.find',
              'uses' => 'Banners\BannersController@find',
          ]);
    Route::post('/', [
        'as' => 'api.banners.store',
        'uses' => 'Banners\BannersController@store',
    ]);

    Route::delete('/{banners}', [
        'as' => 'api.banners.destroy',
        'uses' => 'Banners\BannersController@destroy',
    ]);
});

Route::middleware('auth:api')->prefix('/attributes')->group(function (){

    Route::get('/', [
        'as' => 'api.attribute.index',
        'uses' => 'Attribute\AttributeController@index',
    ]);
    Route::post('/{attribute}/edit', [
            'as' => 'api.attribute.update',
            'uses' => 'Attribute\AttributeController@update',
        ]);
   Route::get('/{attribute}', [
              'as' => 'api.attribute.find',
              'uses' => 'Attribute\AttributeController@find',
          ]);
    Route::post('/', [
        'as' => 'api.attribute.store',
        'uses' => 'Attribute\AttributeController@store',
    ]);

    Route::delete('/{attribute}', [
        'as' => 'api.attribute.destroy',
        'uses' => 'Attribute\AttributeController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/products')->group(function (){

    Route::get('/', [
        'as' => 'api.product.index',
        'uses' => 'Product\ProductController@index',
    ]);
    Route::post('/{product}/edit', [
            'as' => 'api.product.update',
            'uses' => 'Product\ProductController@update',
        ]);
   Route::get('/{product}', [
              'as' => 'api.product.find',
              'uses' => 'Product\ProductController@find',
          ]);
    Route::post('/', [
        'as' => 'api.product.store',
        'uses' => 'Product\ProductController@store',
    ]);

    Route::delete('/{product}', [
        'as' => 'api.product.destroy',
        'uses' => 'Product\ProductController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/pinformations')->group(function (){

    Route::get('/', [
        'as' => 'api.pinformation.index',
        'uses' => 'PInformation\PInformationController@index',
    ]);
    Route::post('/{pinformation}/edit', [
            'as' => 'api.pinformation.update',
            'uses' => 'PInformation\PInformationController@update',
        ]);
   Route::get('/{pinformation}', [
              'as' => 'api.pinformation.find',
              'uses' => 'PInformation\PInformationController@find',
          ]);
    Route::post('/', [
        'as' => 'api.pinformation.store',
        'uses' => 'PInformation\PInformationController@store',
    ]);

    Route::delete('/{pinformation}', [
        'as' => 'api.pinformation.destroy',
        'uses' => 'PInformation\PInformationController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/vouchers')->group(function (){

    Route::get('/', [
        'as' => 'api.admin.voucher.index',
        'uses' => 'Voucher\VoucherController@index',
    ]);
    Route::post('/{voucher}/edit', [
            'as' => 'api.admin.voucher.update',
            'uses' => 'Voucher\VoucherController@update',
        ]);
   Route::get('/{voucher}', [
              'as' => 'api.admin.voucher.find',
              'uses' => 'Voucher\VoucherController@find',
          ]);
    Route::post('/', [
        'as' => 'api.admin.voucher.store',
        'uses' => 'Voucher\VoucherController@store',
    ]);

    Route::delete('/{voucher}', [
        'as' => 'api.admin.voucher.destroy',
        'uses' => 'Voucher\VoucherController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/ranks')->group(function (){

    Route::get('/', [
        'as' => 'api.rank.index',
        'uses' => 'Rank\RankController@index',
    ]);
    Route::post('/{rank}/edit', [
            'as' => 'api.rank.update',
            'uses' => 'Rank\RankController@update',
        ]);
   Route::get('/{rank}', [
              'as' => 'api.rank.find',
              'uses' => 'Rank\RankController@find',
          ]);
    Route::post('/', [
        'as' => 'api.rank.store',
        'uses' => 'Rank\RankController@store',
    ]);

    Route::delete('/{rank}', [
        'as' => 'api.rank.destroy',
        'uses' => 'Rank\RankController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/shiptypes')->group(function (){

    Route::get('/', [
        'as' => 'api.shiptype.index',
        'uses' => 'ShipType\ShipTypeController@index',
    ]);
    Route::post('/{shiptype}/edit', [
            'as' => 'api.shiptype.update',
            'uses' => 'ShipType\ShipTypeController@update',
        ]);
   Route::get('/{shiptype}', [
              'as' => 'api.shiptype.find',
              'uses' => 'ShipType\ShipTypeController@find',
          ]);
    Route::post('/', [
        'as' => 'api.shiptype.store',
        'uses' => 'ShipType\ShipTypeController@store',
    ]);

    Route::delete('/{shiptype}', [
        'as' => 'api.shiptype.destroy',
        'uses' => 'ShipType\ShipTypeController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/orders')->group(function (){

    Route::get('/', [
        'as' => 'api.orders.index',
        'uses' => 'Orders\OrdersController@index',
    ]);
    Route::post('/{orders}/edit', [
            'as' => 'api.orders.update',
            'uses' => 'Orders\OrdersController@update',
        ]);
   Route::get('/{orders}', [
              'as' => 'api.orders.find',
              'uses' => 'Orders\OrdersController@find',
          ]);
    Route::post('/', [
        'as' => 'api.orders.store',
        'uses' => 'Orders\OrdersController@store',
    ]);

    Route::delete('/{orders}', [
        'as' => 'api.orders.destroy',
        'uses' => 'Orders\OrdersController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/paymentmethods')->group(function (){

    Route::get('/', [
        'as' => 'api.paymentmethod.index',
        'uses' => 'PaymentMethod\PaymentMethodController@index',
    ]);
    Route::post('/{paymentmethod}/edit', [
            'as' => 'api.paymentmethod.update',
            'uses' => 'PaymentMethod\PaymentMethodController@update',
        ]);
   Route::get('/{paymentmethod}', [
              'as' => 'api.paymentmethod.find',
              'uses' => 'PaymentMethod\PaymentMethodController@find',
          ]);
    Route::post('/', [
        'as' => 'api.paymentmethod.store',
        'uses' => 'PaymentMethod\PaymentMethodController@store',
    ]);

    Route::delete('/{paymentmethod}', [
        'as' => 'api.paymentmethod.destroy',
        'uses' => 'PaymentMethod\PaymentMethodController@destroy',
    ]);
});
// append

















