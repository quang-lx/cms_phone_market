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
});

Route::middleware(['auth:api'])->prefix('media')->group(function ($router) {
	Route::post('file', [
		'uses' => 'MediaController@store',
		'as' => 'apife.media.store',
	]);
});


Route::prefix('/home')->group(function () {

	Route::get('/', [
		'as' => 'apife.home.index',
		'uses' => 'HomeController@index',
	]);

});
