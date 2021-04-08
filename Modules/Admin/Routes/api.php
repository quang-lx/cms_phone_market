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
// append






