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
    Route::get('/shop-permissions', [
        'as' => 'admin.shoppermissions.shopindex',
        'uses' => 'Auth\PermissionController@shopindex',

    ])->middleware('permission:admin.shoppermissions.shopindex');

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


    //shop role
    Route::get('/shop-roles', [
        'as' => 'admin.shoproles.index',
        'uses' => 'Auth\RoleController@index',

    ])->middleware('permission:admin.shoproles.index');
    Route::get('shop-roles/create', [
        'as' => 'admin.shoproles.create',
        'uses' => 'Auth\RoleController@create',
    ])->middleware('permission:admin.shoproles.create');

    Route::get('shop-roles/{role}/edit', [
        'as' => 'admin.shoproles.edit',
        'uses' => 'Auth\RoleController@edit',
    ])->middleware('permission:admin.shoproles.edit');
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

Route::group(['prefix' => '/province'], function ( ) {

    Route::get('/', [
        'as' => 'admin.province.index',
        'uses' => 'Province\ProvinceController@index',
        'middleware' => 'permission:admin.province.index'
    ]);
    Route::get('create', [
        'as' => 'admin.province.create',
        'uses' => 'Province\ProvinceController@create',
        'middleware' => 'permission:admin.province.create'
    ]);

    Route::get('{province}/edit', [
        'as' => 'admin.province.edit',
        'uses' => 'Province\ProvinceController@edit',
        'middleware' => 'permission:admin.province.edit'
    ]);


});
Route::group(['prefix' => '/district'], function ( ) {

    Route::get('/', [
        'as' => 'admin.district.index',
        'uses' => 'District\DistrictController@index',
        'middleware' => 'permission:admin.district.index'
    ]);
    Route::get('create', [
        'as' => 'admin.district.create',
        'uses' => 'District\DistrictController@create',
        'middleware' => 'permission:admin.district.create'
    ]);

    Route::get('{district}/edit', [
        'as' => 'admin.district.edit',
        'uses' => 'District\DistrictController@edit',
        'middleware' => 'permission:admin.district.edit'
    ]);


});
Route::group(['prefix' => '/phoenix'], function ( ) {

    Route::get('/', [
        'as' => 'admin.phoenix.index',
        'uses' => 'Phoenix\PhoenixController@index',
        'middleware' => 'permission:admin.phoenix.index'
    ]);
    Route::get('create', [
        'as' => 'admin.phoenix.create',
        'uses' => 'Phoenix\PhoenixController@create',
        'middleware' => 'permission:admin.phoenix.create'
    ]);

    Route::get('{phoenix}/edit', [
        'as' => 'admin.phoenix.edit',
        'uses' => 'Phoenix\PhoenixController@edit',
        'middleware' => 'permission:admin.phoenix.edit'
    ]);


});
Route::group(['prefix' => '/company'], function ( ) {

    Route::get('/', [
        'as' => 'admin.company.index',
        'uses' => 'Company\CompanyController@index',
        'middleware' => 'permission:admin.company.index'
    ]);

    Route::get('danh-sach', [
        'as' => 'admin.company.index1',
        'uses' => 'Company\CompanyController@index1',
        'middleware' => 'permission:admin.company.index'

    ]);


    Route::get('create', [
        'as' => 'admin.company.create',
        'uses' => 'Company\CompanyController@create',
        'middleware' => 'permission:admin.company.create'
    ]);

    Route::get('{company}/edit', [
        'as' => 'admin.company.edit',
        'uses' => 'Company\CompanyController@edit',
        'middleware' => 'permission:admin.company.edit'
    ]);

    Route::get('{company}/chi-tiet', [
        'as' => 'admin.company.detail',
        'uses' => 'Company\CompanyController@detail',
        'middleware' => 'permission:admin.company.detail'
    ]);

    Route::get('uu-tien', [
        'as' => 'admin.company.priority',
        'uses' => 'Company\CompanyController@priority',
        'middleware' => 'permission:admin.company.priority'
    ]);


});
Route::group(['prefix' => '/pcategory'], function ( ) {

    Route::get('/', [
        'as' => 'admin.pcategory.index',
        'uses' => 'Pcategory\PcategoryController@index',
        'middleware' => 'permission:admin.pcategory.index'
    ]);
    Route::get('create', [
        'as' => 'admin.pcategory.create',
        'uses' => 'Pcategory\PcategoryController@create',
        'middleware' => 'permission:admin.pcategory.create'
    ]);

    Route::get('{pcategory}/edit', [
        'as' => 'admin.pcategory.edit',
        'uses' => 'Pcategory\PcategoryController@edit',
        'middleware' => 'permission:admin.pcategory.edit'
    ]);


});
Route::group(['prefix' => '/brand'], function ( ) {

    Route::get('/', [
        'as' => 'admin.brand.index',
        'uses' => 'Brand\BrandController@index',
        'middleware' => 'permission:admin.brand.index'
    ]);
    Route::get('create', [
        'as' => 'admin.brand.create',
        'uses' => 'Brand\BrandController@create',
        'middleware' => 'permission:admin.brand.create'
    ]);

    Route::get('{brand}/edit', [
        'as' => 'admin.brand.edit',
        'uses' => 'Brand\BrandController@edit',
        'middleware' => 'permission:admin.brand.edit'
    ]);


});
Route::group(['prefix' => '/account'], function ( ) {

    Route::get('/', [
        'as' => 'admin.account.index',
        'uses' => 'Account\AccountController@index',
        'middleware' => 'permission:admin.account.index'
    ]);
    Route::get('create', [
        'as' => 'admin.account.create',
        'uses' => 'Account\AccountController@create',
        'middleware' => 'permission:admin.account.create'
    ]);

    Route::get('{account}/edit', [
        'as' => 'admin.account.edit',
        'uses' => 'Account\AccountController@edit',
        'middleware' => 'permission:admin.account.edit'
    ]);


});
Route::group(['prefix' => '/problem'], function ( ) {

    Route::get('/', [
        'as' => 'admin.problem.index',
        'uses' => 'Problem\ProblemController@index',
        'middleware' => 'permission:admin.problem.index'
    ]);
    Route::get('create', [
        'as' => 'admin.problem.create',
        'uses' => 'Problem\ProblemController@create',
        'middleware' => 'permission:admin.problem.create'
    ]);

    Route::get('{problem}/edit', [
        'as' => 'admin.problem.edit',
        'uses' => 'Problem\ProblemController@edit',
        'middleware' => 'permission:admin.problem.edit'
    ]);


});
// append








