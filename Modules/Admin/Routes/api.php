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


Route::middleware('auth:api')->prefix('/banner')->group(function (){
    // alarm level

    Route::get('/', [
        'as' => 'api.banner.index',
        'uses' => 'Banner\BannerController@index',

    ]);
    Route::delete('/{banner}', [
        'as' => 'api.banner.destroy',
        'uses' => 'Banner\BannerController@destroy',

    ]);

    Route::get('/{banner}', [
        'as' => 'api.banner.find',
        'uses' => 'Banner\BannerController@find',
    ]);
    Route::post('/{banner}/edit', [
        'as' => 'api.banner.update',
        'uses' => 'Banner\BannerController@update',

    ]);
    Route::post('/', [
        'as' => 'api.banner.store',
        'uses' => 'Banner\BannerController@store',

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

Route::middleware('auth:api')->prefix('/partner')->group(function (){
    // alarm level

    Route::get('/', [
        'as' => 'api.partner.index',
        'uses' => 'Partner\PartnerController@index',

    ]);
    Route::delete('/{partner}', [
        'as' => 'api.partner.destroy',
        'uses' => 'Partner\PartnerController@destroy',

    ]);

    Route::get('/{partner}', [
        'as' => 'api.partner.find',
        'uses' => 'Partner\PartnerController@find',
    ]);
    Route::post('/{partner}/edit', [
        'as' => 'api.partner.update',
        'uses' => 'Partner\PartnerController@update',

    ]);
    Route::post('/', [
        'as' => 'api.partner.store',
        'uses' => 'Partner\PartnerController@store',

    ]);

});

