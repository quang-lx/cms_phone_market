<?php

/*
|--------------------------------------------------------------------------
| Web shop Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Route;

Route::get('/', [
    'as' => 'shop.dashboard.index',
    'uses' => 'HomeController@index',

]);


Route::group(['prefix' =>'/auth'], function (){

    // role
    Route::get('/roles', [
        'as' => 'shop.roles.index',
        'uses' => 'Auth\RoleController@index',

    ])->middleware('permission:shop.roles.index');
    Route::get('roles/create', [
        'as' => 'shop.roles.create',
        'uses' => 'Auth\RoleController@create',
    ])->middleware('permission:shop.roles.create');

    Route::get('roles/{role}/edit', [
        'as' => 'shop.roles.edit',
        'uses' => 'Auth\RoleController@edit',
    ])->middleware('permission:shop.roles.edit');


});

Route::group(['prefix' => '/shop'], function ( ) {

    Route::get('/', [
        'as' => 'shop.shop.index',
        'uses' => 'Shop\ShopController@index',
        'middleware' => 'permission:shop.shop.index'
    ]);
    Route::get('create', [
        'as' => 'shop.shop.create',
        'uses' => 'Shop\ShopController@create',
        'middleware' => 'permission:shop.shop.create'
    ]);

    Route::get('{shop}/edit', [
        'as' => 'shop.shop.edit',
        'uses' => 'Shop\ShopController@edit',
        'middleware' => 'permission:shop.shop.edit'
    ]);


});
Route::group(['prefix' => '/user'], function ( ) {

    Route::get('/', [
        'as' => 'shop.user.index',
        'uses' => 'User\UserController@index',
        'middleware' => 'permission:shop.user.index'
    ]);
    Route::get('create', [
        'as' => 'shop.user.create',
        'uses' => 'User\UserController@create',
        'middleware' => 'permission:shop.user.create'
    ]);

    Route::get('{user}/edit', [
        'as' => 'shop.user.edit',
        'uses' => 'User\UserController@edit',
        'middleware' => 'permission:shop.user.edit'
    ]);


});
Route::group(['prefix' => '/product'], function ( ) {

    Route::get('/', [
        'as' => 'shop.product.index',
        'uses' => 'Product\ProductController@index',
        'middleware' => 'permission:shop.product.index'
    ]);
    Route::get('create', [
        'as' => 'shop.product.create',
        'uses' => 'Product\ProductController@create',
        'middleware' => 'permission:shop.product.create'
    ]);

    Route::get('{product}/edit', [
        'as' => 'shop.product.edit',
        'uses' => 'Product\ProductController@edit',
        'middleware' => 'permission:shop.product.edit'
    ]);


});
Route::group(['prefix' => '/company'], function ( ) {

    Route::get('/', [
        'as' => 'shop.company.index',
        'uses' => 'Company\CompanyController@index',
        'middleware' => 'permission:shop.company.index'
    ]);
    Route::get('create', [
        'as' => 'shop.company.create',
        'uses' => 'Company\CompanyController@create',
        'middleware' => 'permission:shop.company.create'
    ]);

    Route::get('edit', [
        'as' => 'shop.company.edit',
        'uses' => 'Company\CompanyController@edit',
        'middleware' => 'permission:shop.company.edit'
    ]);


});
Route::group(['prefix' => '/attribute'], function ( ) {

    Route::get('/', [
        'as' => 'shop.attribute.index',
        'uses' => 'Attribute\AttributeController@index',
        'middleware' => 'permission:shop.attribute.index'
    ]);
    Route::get('create', [
        'as' => 'shop.attribute.create',
        'uses' => 'Attribute\AttributeController@create',
        'middleware' => 'permission:shop.attribute.create'
    ]);

    Route::get('{attribute}/edit', [
        'as' => 'shop.attribute.edit',
        'uses' => 'Attribute\AttributeController@edit',
        'middleware' => 'permission:shop.attribute.edit'
    ]);


});
Route::group(['prefix' => '/voucher'], function ( ) {

    Route::get('/', [
        'as' => 'shop.voucher.index',
        'uses' => 'Voucher\VoucherController@index',
        'middleware' => 'permission:shop.voucher.index'
    ]);
    Route::get('create', [
        'as' => 'shop.voucher.create',
        'uses' => 'Voucher\VoucherController@create',
        'middleware' => 'permission:shop.voucher.create'
    ]);

    Route::get('{voucher}/edit', [
        'as' => 'shop.voucher.edit',
        'uses' => 'Voucher\VoucherController@edit',
        'middleware' => 'permission:shop.voucher.edit'
    ]);


});
Route::group(['prefix' => '/pinformation'], function ( ) {

    Route::get('/', [
        'as' => 'shop.pinformation.index',
        'uses' => 'PInformation\PInformationController@index',
        'middleware' => 'permission:shop.pinformation.index'
    ]);
    Route::get('create', [
        'as' => 'shop.pinformation.create',
        'uses' => 'PInformation\PInformationController@create',
        'middleware' => 'permission:shop.pinformation.create'
    ]);

    Route::get('{pinformation}/edit', [
        'as' => 'shop.pinformation.edit',
        'uses' => 'PInformation\PInformationController@edit',
        'middleware' => 'permission:shop.pinformation.edit'
    ]);


});
Route::group(['prefix' => '/vtcategory'], function ( ) {

    Route::get('/', [
        'as' => 'shop.vtcategory.index',
        'uses' => 'VtCategory\VtCategoryController@index',
        'middleware' => 'permission:shop.vtcategory.index'
    ]);
    Route::get('create', [
        'as' => 'shop.vtcategory.create',
        'uses' => 'VtCategory\VtCategoryController@create',
        'middleware' => 'permission:shop.vtcategory.create'
    ]);

    Route::get('{vtcategory}/edit', [
        'as' => 'shop.vtcategory.edit',
        'uses' => 'VtCategory\VtCategoryController@edit',
        'middleware' => 'permission:shop.vtcategory.edit'
    ]);


});
Route::group(['prefix' => '/vtproduct'], function ( ) {

    Route::get('/', [
        'as' => 'shop.vtproduct.index',
        'uses' => 'VtProduct\VtProductController@index',
        'middleware' => 'permission:shop.vtproduct.index'
    ]);
    Route::get('create', [
        'as' => 'shop.vtproduct.create',
        'uses' => 'VtProduct\VtProductController@create',
        'middleware' => 'permission:shop.vtproduct.create'
    ]);

    Route::get('{vtproduct}/edit', [
        'as' => 'shop.vtproduct.edit',
        'uses' => 'VtProduct\VtProductController@edit',
        'middleware' => 'permission:shop.vtproduct.edit'
    ]);


});

Route::group(['prefix' => '/transfer'], function ( ) {

    Route::get('/', [
        'as' => 'shop.transfer.index',
        'uses' => 'TransferHistory\TransferHistoryController@index',
        'middleware' => 'permission:shop.transfer.index'
    ]);
    Route::get('create', [
        'as' => 'shop.transfer.create',
        'uses' => 'TransferHistory\TransferHistoryController@create',
        'middleware' => 'permission:shop.transfer.create'
    ]);

    Route::get('{transfer}/edit', [
        'as' => 'shop.transfer.edit',
        'uses' => 'TransferHistory\TransferHistoryController@edit',
        'middleware' => 'permission:shop.transfer.edit'
    ]);
});

Route::group(['prefix' => '/vtimportexcel'], function ( ) {

    Route::get('/', [
        'as' => 'shop.vtimportexcel.index',
        'uses' => 'VtImportExcel\VtImportExcelController@index',
        'middleware' => 'permission:shop.vtimportexcel.index'
    ]);
    Route::get('create', [
        'as' => 'shop.vtimportexcel.create',
        'uses' => 'VtImportExcel\VtImportExcelController@create',
        'middleware' => 'permission:shop.vtimportexcel.create'
    ]);

    Route::get('{vtimportexcel}/detail', [
        'as' => 'shop.vtimportexcel.detail',
        'uses' => 'VtImportExcel\VtImportExcelController@detail',
        'middleware' => 'permission:shop.vtimportexcel.detail'
    ]);


});
Route::group(['prefix' => '/storageproduct'], function ( ) {

    Route::get('/', [
        'as' => 'shop.storageproduct.index',
        'uses' => 'StorageProduct\StorageProductController@index',
        'middleware' => 'permission:shop.storageproduct.index'
    ]);

    Route::get('{storageproduct}/edit', [
        'as' => 'shop.storageproduct.edit',
        'uses' => 'StorageProduct\StorageProductController@edit',
        'middleware' => 'permission:shop.storageproduct.edit'
    ]);


});
Route::group(['prefix' => '/shopshiptype'], function ( ) {

    Route::get('/', [
        'as' => 'shop.shopshiptype.index',
        'uses' => 'ShopShipType\ShopShipTypeController@index',
        'middleware' => 'permission:shop.shopshiptype.index'
    ]);


});
Route::group(['prefix' => '/orders'], function ( ) {

    Route::get('/', [
        'as' => 'shop.orders.index',
        'uses' => 'Orders\OrdersController@index',
        'middleware' => 'permission:shop.orders.index'
    ]);

    Route::get('/guarantee', [
        'as' => 'shop.ordersguarantee.index',
        'uses' => 'Orders\OrdersController@index',
        'middleware' => 'permission:shop.orders.index'
    ]);

    Route::get('/buysell', [
        'as' => 'shop.ordersbuysell.index',
        'uses' => 'Orders\OrdersController@index',
        'middleware' => 'permission:shop.orders.index'
    ]);

    Route::get('{orders}/detail-buysell', [
        'as' => 'shop.orders.detailbuysell',
        'uses' => 'Orders\OrdersController@detail',
        'middleware' => 'permission:shop.orders.detail'
    ]);

    Route::get('{orders}/detail', [
        'as' => 'shop.orders.detail',
        'uses' => 'Orders\OrdersController@detail',
        'middleware' => 'permission:shop.orders.detail'
    ]);

    Route::get('{orders}/detail-guarantee', [
        'as' => 'shop.orders.detailguarantee',
        'uses' => 'Orders\OrdersController@detail',
        'middleware' => 'permission:shop.orders.detail'
    ]);

    


});
Route::group(['prefix' => '/shopcategory'], function ( ) {

    Route::get('/', [
        'as' => 'shop.shopcategory.index',
        'uses' => 'ShopCategory\ShopCategoryController@index',
        'middleware' => 'permission:shop.shopcategory.index'
    ]);


});
Route::group(['prefix' => '/vtshopproduct'], function ( ) {

    Route::get('/', [
        'as' => 'shop.vtshopproduct.index',
        'uses' => 'VtShopProduct\VtShopProductController@index',
        'middleware' => 'permission:shop.vtshopproduct.index'
    ]);
    Route::get('create', [
        'as' => 'shop.vtshopproduct.create',
        'uses' => 'VtShopProduct\VtShopProductController@create',
        'middleware' => 'permission:shop.vtshopproduct.create'
    ]);
});
Route::group(['prefix' => '/shopordernotification'], function ( ) {

    Route::get('/', [
        'as' => 'admin.shopordernotification.index',
        'uses' => 'ShopOrderNotification\ShopOrderNotificationController@index',
        'middleware' => 'permission:admin.shopordernotification.index'
    ]);
    Route::get('create', [
        'as' => 'admin.shopordernotification.create',
        'uses' => 'ShopOrderNotification\ShopOrderNotificationController@create',
        'middleware' => 'permission:admin.shopordernotification.create'
    ]);

    Route::get('{shopordernotification}/edit', [
        'as' => 'admin.shopordernotification.edit',
        'uses' => 'ShopOrderNotification\ShopOrderNotificationController@edit',
        'middleware' => 'permission:admin.shopordernotification.edit'
    ]);


});
// append














