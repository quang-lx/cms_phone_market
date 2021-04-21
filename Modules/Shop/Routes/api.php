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
Route::middleware('auth:api')->prefix('auth')->group(function (){
    Route::get('/permissions/all-by-group', [
        'as' => 'apishop.permissions.all-by-group',
        'uses' => 'Auth\PermissionController@allByGroup',

    ]);

    //roles

    Route::get('/roles/all', [
        'as' => 'apishop.roles.all',
        'uses' => 'Auth\RoleController@all',

    ]);
    Route::get('/roles/allByCompany', [
        'as' => 'apishop.roles.allByCompany',
        'uses' => 'Auth\RoleController@allByCompany',

    ]);
    Route::get('/roles', [
        'as' => 'apishop.roles.index',
        'uses' => 'Auth\RoleController@index',

    ]);
    Route::get('/roles', [
        'as' => 'apishop.roles.index',
        'uses' => 'Auth\RoleController@index',

    ]);
    Route::delete('roles/{role}', [
        'as' => 'apishop.roles.destroy',
        'uses' => 'Auth\RoleController@destroy',

    ]);
    Route::post('roles', [
        'as' => 'apishop.roles.store',
        'uses' => 'Auth\RoleController@store',

    ]);
    Route::get('roles/find-new', [
        'as' => 'apishop.roles.find-new',
        'uses' => 'Auth\RoleController@findNew',

    ]);
    Route::get('roles/{role}', [
        'as' => 'apishop.roles.find',
        'uses' => 'Auth\RoleController@find',

    ]);


    Route::post('roles/{role}/edit', [
        'as' => 'apishop.roles.update',
        'uses' => 'Auth\RoleController@update',

    ]);
    Route::post('roles/{role}/assign-permission', [
        'as' => 'apishop.roles.assignPermissions',
        'uses' => 'Auth\RoleController@assignPermissions',

    ]);
    Route::post('roles/{role}/remove-permission', [
        'as' => 'apishop.roles.removePechangePasswordrmissions',
        'uses' => 'Auth\RoleController@removePermissions',

    ]);



});

Route::middleware('auth:api')->prefix('/shop')->group(function (){

    Route::get('/', [
        'as' => 'api.shop.index',
        'uses' => 'Shop\ShopController@index',
    ]);
    Route::post('/{shop}/edit', [
        'as' => 'api.shop.update',
        'uses' => 'Shop\ShopController@update',
    ]);
    Route::get('/{shop}', [
        'as' => 'api.shop.find',
        'uses' => 'Shop\ShopController@find',
    ]);
    Route::post('/', [
        'as' => 'api.shop.store',
        'uses' => 'Shop\ShopController@store',
    ]);

    Route::delete('/{shop}', [
        'as' => 'api.shop.destroy',
        'uses' => 'Shop\ShopController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/users')->group(function (){

    Route::get('/', [
        'as' => 'api.user.index',
        'uses' => 'User\UserController@index',
    ]);
    Route::post('/{user}/edit', [
            'as' => 'api.user.update',
            'uses' => 'User\UserController@update',
        ]);
   Route::get('/{user}', [
              'as' => 'api.user.find',
              'uses' => 'User\UserController@find',
          ]);
    Route::post('/', [
        'as' => 'api.user.store',
        'uses' => 'User\UserController@store',
    ]);

    Route::delete('/{user}', [
        'as' => 'api.user.destroy',
        'uses' => 'User\UserController@destroy',
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
	Route::get('/data/tree', [
		'as' => 'apishop.product.tree',
		'uses' => 'Product\ProductController@tree',
	]);


});

Route::middleware('auth:api')->prefix('/companies')->group(function (){

    Route::get('/', [
        'as' => 'apishop.company.index',
        'uses' => 'Company\CompanyController@index',
    ]);
    Route::post('/edit', [
            'as' => 'apishop.company.update',
            'uses' => 'Company\CompanyController@update',
        ]);
    Route::post('/', [
        'as' => 'apishop.company.store',
        'uses' => 'Company\CompanyController@store',
    ]);

    Route::delete('/{company}', [
        'as' => 'apishop.company.destroy',
        'uses' => 'Company\CompanyController@destroy',
    ]);

    Route::get('/chi-tiet', [
        'as' => 'apishop.company.find',
        'uses' => 'Company\CompanyController@find',
    ]);

    Route::post('/change-password', [
        'as' => 'apishop.company.changePassword',
        'uses' => 'Company\CompanyController@changePassword',
    ]);


    Route::get('/', [
		'as' => 'apishop.pcategory.index',
		'uses' => 'Pcategory\PcategoryController@index',
	]);

});

Route::middleware('auth:api')->prefix('/brands')->group(function (){

    Route::get('/', [
        'as' => 'apishop.brand.index',
        'uses' => 'Brand\BrandController@index',
    ]);
});
Route::middleware('auth:api')->prefix('/vouchers')->group(function (){

    Route::get('/', [
        'as' => 'api.voucher.index',
        'uses' => 'Voucher\VoucherController@index',
    ]);
    Route::post('/{voucher}/edit', [
            'as' => 'api.voucher.update',
            'uses' => 'Voucher\VoucherController@update',
        ]);
    Route::get('/{voucher}', [
              'as' => 'api.voucher.find',
              'uses' => 'Voucher\VoucherController@find',
          ]);
    Route::post('/', [
        'as' => 'api.voucher.store',
        'uses' => 'Voucher\VoucherController@store',
    ]);

    Route::delete('/{voucher}', [
        'as' => 'api.voucher.destroy',
        'uses' => 'Voucher\VoucherController@destroy',
    ]);
    
});
// append




