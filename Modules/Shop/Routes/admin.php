<?php

/*
|--------------------------------------------------------------------------
| Web Admin Routes
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
// append
