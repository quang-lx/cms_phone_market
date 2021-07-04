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

Route::prefix('/app')->group(function () {
	Route::get('/gifts', [
		'as' => 'api.app.gifts',
		'uses' => 'AppController@gifts',
	]);
    Route::get('/setting', [
        'as' => 'api.app.setting',
        'uses' => 'AppController@setting',
    ]);
    Route::get('/provinces', [
        'as' => 'api.app.provinces',
        'uses' => 'AppController@provinces',
    ]);
    Route::get('/districts', [
        'as' => 'api.app.districts',
        'uses' => 'AppController@districts',
    ]);
    Route::get('/phoenixes', [
        'as' => 'api.app.phoenixes',
        'uses' => 'AppController@phoenixes',
    ]);
    Route::get('/area', [
        'as' => 'api.app.area',
        'uses' => 'AppController@allArea',
    ]);
    Route::get('shop/nearest', [
        'uses' => 'ShopController@shopNearest',
        'as' => 'apife.shop.shopNearest',
    ]);
    Route::get('/ship-type', [
        'as' => 'api.app.shipType',
        'uses' => 'AppController@listShipType',
    ]);
	Route::get('/payment-method', [
		'as' => 'api.app.paymentmethod',
		'uses' => 'AppController@listPaymentMethod',
	]);
    Route::get('/vouchers', [
        'as' => 'api.app.vouchers',
        'uses' => 'AppController@vouchers',
    ]);
});


Route::prefix('auth')->group(function () {
    Route::post('/login', [
        'as' => 'api.auth.login',
        'uses' => 'AuthController@login',

    ]);
    Route::post('/register', [
        'as' => 'api.auth.register',
        'uses' => 'AuthController@register',

    ]);
    Route::post('/register/{user}/verify-token', [
        'as' => 'api.auth.verifySmsToken',
        'uses' => 'AuthController@verifySmsToken',

    ]);
    Route::post('/register/{user}/set-password', [
        'as' => 'api.auth.storePassword',
        'uses' => 'AuthController@storePassword',

    ]);
    Route::post('/forgot-password', [
        'as' => 'api.auth.forgotPassword',
        'uses' => 'AuthController@forgotPassword',

    ]);
    Route::post('/{user}/forgot-password-otp', [
        'as' => 'api.auth.forgotPasswordOtp',
        'uses' => 'AuthController@forgotPasswordSendOtp',

    ]);
    Route::post('/{user}/check-password', [
        'as' => 'api.auth.checkPassword',
        'uses' => 'AuthController@checkPassword',

    ]);
    Route::post('/login-social', [
        'as' => 'api.auth.loginSocial',
        'uses' => 'AuthController@loginSocial',

    ]);
});
Route::prefix('otp')->group(function () {

    Route::post('/{user}/resend', [
        'as' => 'api.otp.resend',
        'uses' => 'AuthController@resendOtp',

    ]);

});


Route::middleware(['auth:api'])->prefix('user')->group(function ($router) {


    Route::post('update', [
        'uses' => 'UserController@update',
        'as' => 'apife.user.update',
    ]);

    Route::post('logout', [
        'uses' => 'UserController@logout',
        'as' => 'apife.user.logout',
    ]);
    Route::post('change-password', [
        'uses' => 'UserController@changePassword',
        'as' => 'apife.user.changePassword',
    ]);
    Route::get('profile', [
        'uses' => 'UserController@profile',
        'as' => 'apife.user.profile',
    ]);
	Route::get('notifications', [
		'uses' => 'UserController@notifications',
		'as' => 'apife.user.notifications',
	]);

	Route::get('point-history', [
		'uses' => 'UserController@pointHistory',
		'as' => 'apife.user.pointHistory',
	]);
	Route::get('my-gifts', [
		'uses' => 'UserController@myGifts',
		'as' => 'apife.user.myGifts',
	]);
	Route::post('exchange-gift', [
		'uses' => 'UserController@exchangeGift',
		'as' => 'apife.user.exchangeGift',
	]);
});


Route::prefix('/home')->group(function () {

    Route::get('/', [
        'as' => 'apife.home.index',
        'uses' => 'HomeController@index',
    ]);
    Route::get('/buy-now/list', [
        'as' => 'apife.home.buyNow',
        'uses' => 'HomeController@buyNow',
    ]);
    Route::get('/best-sell/list', [
        'as' => 'apife.home.bestSell',
        'uses' => 'HomeController@bestSell',
    ]);
});


Route::prefix('/search')->group(function () {
    Route::get('/', [
        'as' => 'apife.search.search',
        'uses' => 'SearchController@search',
    ]);
    Route::get('/suggestion', [
        'as' => 'apife.search.suggestion',
        'uses' => 'SearchController@listSuggestion',
    ]);

});

Route::prefix('/product')->group(function () {
    Route::get('/', [
        'as' => 'apife.product.list',
        'uses' => 'ProductController@listByCategory',
    ]);
    Route::get('/by-service', [
        'as' => 'apife.product.listByService',
        'uses' => 'ProductController@listByService',
    ]);
    Route::get('/{id}/detail', [
        'as' => 'apife.product.detail',
        'uses' => 'ProductController@detail',
    ]);
    Route::get('/{id}/related', [
        'as' => 'apife.product.related',
        'uses' => 'ProductController@related',
    ]);
    Route::get('/{id}/suggested', [
        'as' => 'apife.product.suggested',
        'uses' => 'ProductController@suggested',
    ]);
    Route::get('/shop/{shop_id}/list', [
        'as' => 'apife.product.listByShop',
        'uses' => 'ProductController@listByShop',
    ]);
});

Route::prefix('/category')->group(function () {
    Route::get('/{category_id}/problem-brand', [
        'as' => 'apife.category.problemBrand',
        'uses' => 'CategoryController@getProblemBrandByCat',
    ]);
    Route::get('/{category_id}/sub', [
        'as' => 'apife.category.sub',
        'uses' => 'CategoryController@listSubCat',
    ]);
    Route::get('/services', [
        'as' => 'apife.category.services',
        'uses' => 'CategoryController@listByServiceType',
    ]);

});
Route::prefix('/shop')->group(function () {
    Route::get('/{id}/detail', [
        'as' => 'apife.shop.detail',
        'uses' => 'ShopController@detail',
    ]);
    Route::get('/{id}/most-popular', [
        'as' => 'apife.shop.mostPopular',
        'uses' => 'ShopController@mostPopular',
    ]);
    Route::get('/search', [
        'as' => 'apife.shop.search',
        'uses' => 'ShopController@search',
    ]);
    Route::get('/suggestion', [
        'as' => 'apife.shop.listSuggestion',
        'uses' => 'ShopController@listSuggestion',
    ]);
});
Route::prefix('rating')->group(function ($router) {

    Route::get('/{product_id}/list', [
        'uses' => 'RatingController@listByProduct',
        'as' => 'apife.rating.listByProduct',
    ]);
    Route::get('/shop/{shop_id}/list', [
        'uses' => 'RatingController@listByShop',
        'as' => 'apife.rating.listByShop',
    ]);
});
Route::middleware(['auth:api'])->prefix('media')->group(function ($router) {
    Route::post('file', [
        'uses' => 'MediaController@store',
        'as' => 'apife.media.store',
    ]);
});

Route::prefix('rank')->group(function ($router) {

    Route::get('/', [
        'uses' => 'RankController@index',
        'as' => 'apife.rank.index',
    ]);

});

Route::middleware(['auth:api'])->group(function ($router) {
    Route::prefix('shop')->group(function ($router) {
        Route::get('bao-hanh', [
            'uses' => 'ShopController@shopBaoHanh',
            'as' => 'apife.shop.baohanh',
        ]);
    });
    Route::prefix('product')->group(function ($router) {
        Route::get('bao-hanh', [
            'uses' => 'ProductController@baohanh',
            'as' => 'apife.product.baohanh',
        ]);
    });
    Route::prefix('rating')->group(function ($router) {
        Route::post('/', [
            'uses' => 'RatingController@store',
            'as' => 'apife.rating.store',
        ]);
        Route::post('/shop', [
            'uses' => 'RatingController@storeShop',
            'as' => 'apife.rating.storeShop',
        ]);
    });

    Route::prefix('user/address')->group(function ($router) {
        Route::post('/', [
            'uses' => 'AddressController@store',
            'as' => 'apife.address.store',
        ]);
        Route::post('/{address}', [
            'uses' => 'AddressController@update',
            'as' => 'apife.address.update',
        ]);
        Route::get('/list', [
            'uses' => 'AddressController@index',
            'as' => 'apife.address.index',
        ]);
	    Route::post('/{address}/delete', [
		    'uses' => 'AddressController@destroy',
		    'as' => 'apife.address.destroy',
	    ]);
    });

    Route::prefix('order')->group(function ($router) {
        Route::post('/place-order', [
            'uses' => 'OrderController@store',
            'as' => 'apife.order.store',
        ]);
        Route::post('/place-order-product', [
            'uses' => 'OrderController@storeBuyProduct',
            'as' => 'apife.order.storeBuyProduct',
        ]);
        Route::get('/list', [
            'uses' => 'OrderController@getList',
            'as' => 'apife.order.getList',
        ]);
	    Route::get('/{id}/detail', [
		    'uses' => 'OrderController@getDetail',
		    'as' => 'apife.order.getDetail',
	    ]);
        Route::post('/check-shop-voucher', [
            'uses' => 'OrderController@getShopDiscountAmount',
            'as' => 'apife.order.getShopDiscountAmount',
        ]);
        Route::post('/check-system-voucher', [
            'uses' => 'OrderController@getSystemDiscountAmount',
            'as' => 'apife.order.getSystemDiscountAmount',
        ]);
		Route::post('/{order}/update-status', [
			'uses' => 'OrderController@updateStatus',
			'as' => 'apife.order.updateStatus',
		]);
    });
	Route::prefix('cart')->group(function ($router) {
		Route::post('/update', [
			'uses' => 'CartController@store',
			'as' => 'apife.cart.store',
		]);
		Route::get('/detail', [
			'uses' => 'CartController@detail',
			'as' => 'apife.cart.detail',
		]);
	});
});


