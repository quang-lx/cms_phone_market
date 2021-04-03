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
        'as' => 'apishop.roles.removePermissions',
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
// append
