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
});
