<?php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => '/admin'], function ( ) {
    Route::get('login', [
        'as' => 'admin.login',
        'uses' => 'Auth\LoginController@showAdminLoginForm',

    ]);
    Route::get('login/facebook', [
        'as' => 'admin.login.fb',
        'uses' => 'Auth\LoginController@facebook',

    ]);
    Route::get('facebook/callback', [
        'as' => 'admin.login.facebookCallback',
        'uses' => 'Auth\LoginController@facebookCallback',

    ]);
	Route::post('login', 'Auth\LoginController@login')->name('admin.login.submit');

});
