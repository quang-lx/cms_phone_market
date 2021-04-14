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
// append



