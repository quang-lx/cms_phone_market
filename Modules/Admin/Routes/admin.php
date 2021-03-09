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
    'as' => 'admin.dashboard.index',
    'uses' => 'HomeController@index',

])->middleware('permission:admin.dashboard.index');

Route::group(['prefix' =>'/profile'], function (){
    Route::get('edit', [
        'as' => 'admin.profile.edit',
        'uses' => 'HomeController@index',

    ])->middleware('permission:admin.profile.edit');
});

Route::group(['prefix' =>'/auth'], function (){
    Route::group(['prefix' => 'quan-tri'], function () {
        Route::get('/', [
            'as' => 'admin.admins.index',
            'uses' => 'Auth\AdminUserController@index',

        ])->middleware('permission:admin.admins.index');
        Route::get('/create', [
            'as' => 'admin.admins.create',
            'uses' => 'Auth\AdminUserController@create',

        ])->middleware('permission:admin.admins.create');
        Route::get('/{user}/edit', [
            'as' => 'admin.admins.edit',
            'uses' => 'Auth\AdminUserController@edit',

        ])->middleware('permission:admin.admins.edit');
    });
    // user
    Route::get('/users', [
        'as' => 'admin.users.index',
        'uses' => 'Auth\UserController@index',

    ])->middleware('permission:admin.users.index');
    Route::get('users/create', [
        'as' => 'admin.users.create',
        'uses' => 'Auth\UserController@create',
    ])->middleware('permission:admin.users.create');

    Route::get('users/{user}/edit', [
        'as' => 'admin.users.edit',
        'uses' => 'Auth\UserController@edit',
    ])->middleware('permission:admin.users.edit');
    // permission
    Route::get('/permissions', [
        'as' => 'admin.permissions.index',
        'uses' => 'Auth\PermissionController@index',

    ])->middleware('permission:admin.permissions.index');
    Route::get('permissions/create', [
        'as' => 'admin.permissions.create',
        'uses' => 'Auth\PermissionController@create',
    ])->middleware('permission:admin.permissions.create');

    Route::get('permissions/{permission}/edit', [
        'as' => 'admin.permissions.edit',
        'uses' => 'Auth\PermissionController@edit',
    ])->middleware('permission:admin.permissions.edit');

    // role
    Route::get('/roles', [
        'as' => 'admin.roles.index',
        'uses' => 'Auth\RoleController@index',

    ])->middleware('permission:admin.roles.index');
    Route::get('roles/create', [
        'as' => 'admin.roles.create',
        'uses' => 'Auth\RoleController@create',
    ])->middleware('permission:admin.roles.create');

    Route::get('roles/{role}/edit', [
        'as' => 'admin.roles.edit',
        'uses' => 'Auth\RoleController@edit',
    ])->middleware('permission:admin.roles.edit');
});
Route::group(['prefix' => '/media'], function ( ) {
    Route::get('media', [
        'as' => 'admin.media.index',
        'uses' => 'Media\MediaController@index',
    ])->middleware('permission:admin.media.index');
    Route::get('media/create', [
        'as' => 'admin.media.create',
        'uses' => 'Media\MediaController@create',
    ])->middleware('permission:admin.media.create');
    Route::post('media', [
        'as' => 'admin.media.store',
        'uses' => 'Media\MediaController@store',
    ])->middleware('permission:admin.media.store');
    Route::get('media/{media}/edit', [
        'as' => 'admin.media.edit',
        'uses' => 'Media\MediaController@edit',
    ])->middleware('permission:admin.media.edit');
    Route::put('media/{media}', [
        'as' => 'admin.media.update',
        'uses' => 'Media\MediaController@update',
    ])->middleware('permission:admin.media.update');
    Route::delete('media/{media}', [
        'as' => 'admin.media.destroy',
        'uses' => 'Media\MediaController@destroy',
    ])->middleware('permission:admin.media.destroy');


});




Route::group(['prefix' => '/danh-muc'], function ( ) {

    Route::get('/', [
        'as' => 'admin.category.index',
        'uses' => 'Category\CategoryController@index',

    ])->middleware('permission:admin.category.index');
    Route::get('/create', [
        'as' => 'admin.category.create',
        'uses' => 'Category\CategoryController@create',

    ])->middleware('permission:admin.category.create');
    Route::get('/{category}/edit', [
        'as' => 'admin.category.edit',
        'uses' => 'Category\CategoryController@edit',

    ])->middleware('permission:admin.category.edit');

});


Route::group(['prefix' => '/tin-tuc'], function ( ) {

    Route::get('/', [
        'as' => 'admin.news.index',
        'uses' => 'News\NewsController@index',

    ])->middleware('permission:admin.news.index');
    Route::get('/create', [
        'as' => 'admin.news.create',
        'uses' => 'News\NewsController@create',

    ])->middleware('permission:admin.news.create');
    Route::get('/{news}/edit', [
        'as' => 'admin.news.edit',
        'uses' => 'News\NewsController@edit',

    ])->middleware('permission:admin.news.edit');

});

