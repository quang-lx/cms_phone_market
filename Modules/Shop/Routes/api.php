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
        'as' => 'apishop.product.index',
        'uses' => 'Product\ProductController@index',
    ]);
    Route::post('/{product}/edit', [
            'as' => 'apishop.product.update',
            'uses' => 'Product\ProductController@update',
        ]);
   Route::get('/{product}', [
              'as' => 'apishop.product.find',
              'uses' => 'Product\ProductController@find',
          ]);
    Route::post('/', [
        'as' => 'apishop.product.store',
        'uses' => 'Product\ProductController@store',
    ]);

    Route::delete('/{product}', [
        'as' => 'apishop.product.destroy',
        'uses' => 'Product\ProductController@destroy',
    ]);
	Route::get('/data/tree', [
		'as' => 'apishop.product.tree',
		'uses' => 'Product\ProductController@tree',
	]);
	Route::get('/problem-by-category/list', [
		'as' => 'apishop.product.problemByCat',
		'uses' => 'Product\ProductController@problemByCat',
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

    Route::post('/changeStatus', [
        'as' => 'api.voucher.changeStatus',
        'uses' => 'Voucher\VoucherController@changeStatus',
    ]);

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

Route::middleware('auth:api')->prefix('/attributes')->group(function (){

    Route::get('/', [
        'as' => 'apishop.attribute.index',
        'uses' => 'Attribute\AttributeController@index',
    ]);
    Route::post('/{attribute}/edit', [
            'as' => 'apishop.attribute.update',
            'uses' => 'Attribute\AttributeController@update',
        ]);
   Route::get('/{attribute}', [
              'as' => 'apishop.attribute.find',
              'uses' => 'Attribute\AttributeController@find',
          ]);
    Route::post('/', [
        'as' => 'apishop.attribute.store',
        'uses' => 'Attribute\AttributeController@store',
    ]);

    Route::delete('/{attribute}', [
        'as' => 'apishop.attribute.destroy',
        'uses' => 'Attribute\AttributeController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/pinformations')->group(function (){

    Route::get('/', [
        'as' => 'apishop.pinformation.index',
        'uses' => 'PInformation\PInformationController@index',
    ]);
    Route::post('/{pinformation}/edit', [
            'as' => 'apishop.pinformation.update',
            'uses' => 'PInformation\PInformationController@update',
        ]);
   Route::get('/{pinformation}', [
              'as' => 'apishop.pinformation.find',
              'uses' => 'PInformation\PInformationController@find',
          ]);
    Route::post('/', [
        'as' => 'apishop.pinformation.store',
        'uses' => 'PInformation\PInformationController@store',
    ]);

    Route::delete('/{pinformation}', [
        'as' => 'apishop.pinformation.destroy',
        'uses' => 'PInformation\PInformationController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/vtcategories')->group(function (){

    Route::get('/', [
        'as' => 'apishop.vtcategory.index',
        'uses' => 'VtCategory\VtCategoryController@index',
    ]);
    Route::post('/{vtcategory}/edit', [
            'as' => 'apishop.vtcategory.update',
            'uses' => 'VtCategory\VtCategoryController@update',
        ]);
   Route::get('/{vtcategory}', [
              'as' => 'apishop.vtcategory.find',
              'uses' => 'VtCategory\VtCategoryController@find',
          ]);
    Route::post('/', [
        'as' => 'apishop.vtcategory.store',
        'uses' => 'VtCategory\VtCategoryController@store',
    ]);

    Route::delete('/{vtcategory}', [
        'as' => 'apishop.vtcategory.destroy',
        'uses' => 'VtCategory\VtCategoryController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/vtproducts')->group(function (){

    Route::get('/', [
        'as' => 'apishop.vtproduct.index',
        'uses' => 'VtProduct\VtProductController@index',
    ]);
    Route::post('/{vtproduct}/edit', [
            'as' => 'apishop.vtproduct.update',
            'uses' => 'VtProduct\VtProductController@update',
        ]);
   Route::get('/{vtproduct}', [
              'as' => 'apishop.vtproduct.find',
              'uses' => 'VtProduct\VtProductController@find',
          ]);
    Route::post('/', [
        'as' => 'apishop.vtproduct.store',
        'uses' => 'VtProduct\VtProductController@store',
    ]);

    Route::delete('/{vtproduct}', [
        'as' => 'apishop.vtproduct.destroy',
        'uses' => 'VtProduct\VtProductController@destroy',
    ]);

    Route::post('/import}', [
        'as' => 'apishop.vtproduct.import',
        'uses' => 'VtProduct\VtProductController@import',
    ]);
});
Route::middleware('auth:api')->prefix('/vtimportexcels')->group(function (){

    Route::get('/', [
        'as' => 'apishop.vtimportexcel.index',
        'uses' => 'VtImportExcel\VtImportExcelController@index',
    ]);
    Route::post('/{vtimportexcel}/edit', [
            'as' => 'apishop.vtimportexcel.update',
            'uses' => 'VtImportExcel\VtImportExcelController@update',
        ]);
   Route::get('/{vtimportexcel}', [
              'as' => 'apishop.vtimportexcel.find',
              'uses' => 'VtImportExcel\VtImportExcelController@find',
          ]);
    Route::post('/', [
        'as' => 'apishop.vtimportexcel.store',
        'uses' => 'VtImportExcel\VtImportExcelController@store',
    ]);

    Route::delete('/{vtimportexcel}', [
        'as' => 'apishop.vtimportexcel.destroy',
        'uses' => 'VtImportExcel\VtImportExcelController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/transfers')->group(function (){

    Route::get('/', [
        'as' => 'apishop.transfer.index',
        'uses' => 'TransferHistory\TransferHistoryController@index',
    ]);
    Route::post('/{transferhistory}/edit', [
            'as' => 'apishop.transfer.update',
            'uses' => 'TransferHistory\TransferHistoryController@update',
        ]);
   Route::get('/{transferhistory}', [
              'as' => 'apishop.transfer.find',
              'uses' => 'TransferHistory\TransferHistoryController@find',
          ]);
    Route::post('/', [
        'as' => 'apishop.transfer.store',
        'uses' => 'TransferHistory\TransferHistoryController@store',
    ]);

    Route::delete('/{transferhistory}', [
        'as' => 'apishop.transfer.destroy',
        'uses' => 'TransferHistory\TransferHistoryController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/storageproducts')->group(function (){

    Route::get('/', [
        'as' => 'apishop.storageproduct.index',
        'uses' => 'StorageProduct\StorageProductController@index',
    ]);
    Route::post('/{storageproduct}/edit', [
            'as' => 'apishop.storageproduct.update',
            'uses' => 'StorageProduct\StorageProductController@update',
        ]);
   Route::get('/{storageproduct}', [
              'as' => 'apishop.storageproduct.find',
              'uses' => 'StorageProduct\StorageProductController@find',
          ]);
    Route::post('/', [
        'as' => 'apishop.storageproduct.store',
        'uses' => 'StorageProduct\StorageProductController@store',
    ]);

    Route::delete('/{storageproduct}', [
        'as' => 'apishop.storageproduct.destroy',
        'uses' => 'StorageProduct\StorageProductController@destroy',
    ]);
});
Route::middleware('auth:api')->prefix('/shopshiptypes')->group(function (){

    Route::get('/', [
        'as' => 'shopapi.shopshiptype.index',
        'uses' => 'ShopShipType\ShopShipTypeController@index',
    ]);
    Route::post('/', [
        'as' => 'shopapi.shopshiptype.create_or_update',
        'uses' => 'ShopShipType\ShopShipTypeController@create_or_update',
    ]);

});
Route::middleware('auth:api')->prefix('/orders')->group(function (){

    Route::get('/', [
        'as' => 'apishop.orders.index',
        'uses' => 'Orders\OrdersController@index',
    ]);

    Route::get('/statistical', [
        'as' => 'apishop.orders.statistical',
        'uses' => 'Orders\OrdersController@statistical',
    ]);
    

   Route::get('/{orders}', [
        'as' => 'apishop.orders.find',
        'uses' => 'Orders\OrdersController@find',
    ]);

    // đơn hàng sửa chữa

    

    Route::post('/{orders}/repair-cancel', [
        'as' => 'apishop.orders.repair_cancel',
        'uses' => 'Orders\OrdersController@repairCancel',
    ]);

    Route::post('/{orders}/repair-confirmed', [
        'as' => 'apishop.orders.repair_confirmed',
        'uses' => 'Orders\OrdersController@repairConfirmed',
    ]);

    Route::post('/{orders}/repair-fixing', [
        'as' => 'apishop.orders.repair_fixing',
        'uses' => 'Orders\OrdersController@repairFixing',
    ]);

    Route::post('/{orders}/repair-fixing-other', [
        'as' => 'apishop.orders.repair_fixing_other',
        'uses' => 'Orders\OrdersController@repairFixingOther',
    ]);

    Route::post('/{orders}/repair-sending', [
        'as' => 'apishop.orders.repair_sending',
        'uses' => 'Orders\OrdersController@repairSending',
    ]);
    
    Route::post('/{orders}/repair-done', [
        'as' => 'apishop.orders.repair_done',
        'uses' => 'Orders\OrdersController@repairDone',
    ]);


    Route::get('/{orders}/buysell', [
        'as' => 'apishop.orders.findbuysell',
        'uses' => 'Orders\OrdersController@findBuySell',
    ]);

    Route::post('/', [
        'as' => 'apishop.orders.store',
        'uses' => 'Orders\OrdersController@store',
    ]);

    Route::delete('/{orders}', [
        'as' => 'apishop.orders.destroy',
        'uses' => 'Orders\OrdersController@destroy',
    ]);
    // đơn hàng mua bán
    Route::post('/{orders}/buysell-confirmed', [
        'as' => 'apishop.orders.buysell_confirmed',
        'uses' => 'Orders\OrdersController@buysellConfirmed',
    ]);

    Route::post('/{orders}/buysell-cancel', [
        'as' => 'apishop.orders.buysell_cancel',
        'uses' => 'Orders\OrdersController@buysellCancel',
    ]);

    Route::post('/{orders}/buysell-done', [
        'as' => 'apishop.orders.buysell_done',
        'uses' => 'Orders\OrdersController@buysellDone',
    ]);

    // đơn hàng bảo hành 
    Route::post('/{orders}/guarantee-cancel', [
        'as' => 'apishop.orders.guarantee_cancel',
        'uses' => 'Orders\OrdersController@guaranteeCancel',
    ]);

    Route::post('/{orders}/guarantee-confirmed', [
        'as' => 'apishop.orders.guarantee_confirmed',
        'uses' => 'Orders\OrdersController@guaranteeConfirmed',
    ]);

    Route::post('/{orders}/guarantee-warranting', [
        'as' => 'apishop.orders.guarantee_warranting',
        'uses' => 'Orders\OrdersController@guaranteeWarranting',
    ]);

    Route::post('/{orders}/guarantee-sending', [
        'as' => 'apishop.orders.guarantee_sending',
        'uses' => 'Orders\OrdersController@guaranteeSending',
    ]);
    
    Route::post('/{orders}/guarantee-done', [
        'as' => 'apishop.orders.guarantee_done',
        'uses' => 'Orders\OrdersController@guaranteeDone',
    ]);

    //tao mới
    Route::post('/storeBuysell', [
        'as' => 'apishop.orders.storeBuysell',
        'uses' => 'Orders\OrdersController@storeBuysell',
    ]);
    
    Route::post('/storeRepair', [
        'as' => 'apishop.orders.storeRepair',
        'uses' => 'Orders\OrdersController@storeRepair',
    ]);

    Route::post('/{orderId}/edit', [
        'as' => 'apishop.orders.updateBuysell',
        'uses' => 'Orders\OrdersController@updateBuysell',
    ]);
});
Route::middleware('auth:api')->prefix('/shopcategories')->group(function (){

    Route::get('/', [
        'as' => 'apishop.shopcategory.index',
        'uses' => 'ShopCategory\ShopCategoryController@index',
    ]);

    Route::post('/', [
        'as' => 'apishop.shopcategory.create_or_delete',
        'uses' => 'ShopCategory\ShopCategoryController@create_or_delete',
    ]);
});

//dashboard
Route::middleware('auth:api')->prefix('/dashboard')->group(function (){

    Route::get('/listShop', [
        'as' => 'apishop.dashboard.listShop',
        'uses' => 'Dashboard\DashboardController@listShop',
    ]);
});

Route::middleware('auth:api')->prefix('/dashboard')->group(function (){

    Route::get('/topProduct', [
        'as' => 'apishop.dashboards.topProduct',
        'uses' => 'Product\ProductController@topProduct',
    ]);
});


Route::middleware('auth:api')->prefix('/vtshopproducts')->group(function (){

    Route::get('/', [
        'as' => 'apishop.vtshopproduct.index',
        'uses' => 'VtShopProduct\VtShopProductController@index',
    ]);
    Route::post('/', [
        'as' => 'apishop.vtshopproduct.store',
        'uses' => 'VtShopProduct\VtShopProductController@store',
    ]);
});
Route::middleware('auth:api')->prefix('/shopordernotifications')->group(function (){

    Route::get('/', [
        'as' => 'apishop.shopordernotification.index',
        'uses' => 'ShopOrderNotification\ShopOrderNotificationController@index',
    ]);

    Route::post('/{shopordernotification}/edit', [
        'as' => 'apishop.shopordernotification.update',
        'uses' => 'ShopOrderNotification\ShopOrderNotificationController@update',
    ]);

    Route::get('/count_unread_notice', [
        'as' => 'apishop.shopordernotification.count_unread_notice',
        'uses' => 'ShopOrderNotification\ShopOrderNotificationController@countUnreadNotice',
    ]);
});
Route::middleware('auth:api')->prefix('/paymentmethods')->group(function (){

    Route::get('/', [
        'as' => 'apishop.paymentmethod.index',
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















