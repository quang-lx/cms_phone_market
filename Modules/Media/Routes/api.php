<?php

use Illuminate\Http\Request;

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
Route::middleware(['auth:api'])->prefix('media')->group(function ($router) {

    Route::bind('file', function ($file) {
        return app(\Modules\Media\Repositories\MediaRepository::class)->find($file);
    });
    Route::bind('folder', function ($file) {
        return app(\Modules\Media\Repositories\FolderRepository::class)->find($file);
    });
    Route::get('folder', [
        'uses' => 'AllNestableFolderController',
        'as' => 'api.media.folders.all-nestable',
    ]);
    Route::post('folder', [
        'uses' => 'FolderController@store',
        'as' => 'api.media.folders.store',
    ]);
    Route::get('folder/breadcrumb/{folder}', [
        'uses' => 'FolderBreadcrumbController',
        'as' => 'api.media.folders.breadcrumb',
    ]);
    Route::post('folder/{folder}', [
        'uses' => 'FolderController@update',
        'as' => 'api.media.folders.update',
    ]);
    Route::delete('folder/{folder}', [
        'uses' => 'FolderController@destroy',
        'as' => 'api.media.folders.destroy',
    ]);

    Route::post('file', [
        'uses' => 'MediaController@store',
        'as' => 'api.media.store',
    ]);
    Route::post('file-dropzone', [
        'uses' => 'MediaController@storeDropzone',
        'as' => 'api.media.store-dropzone',
    ]);
    Route::post('media/link', [
        'uses' => 'MediaController@linkMedia',
        'as' => 'api.media.link',
    ]);
    Route::post('media/unlink', [
        'uses' => 'MediaController@unlinkMedia',
        'as' => 'api.media.unlink',
    ]);
    Route::post('media/move', [
        'uses' => 'MoveMediaController',
        'as' => 'api.media.media.move',
    ]);
    Route::get('media/all', [
        'uses' => 'MediaController@all',
        'as' => 'api.media.all',
    ]);
    Route::get('media/all-vue', [
        'uses' => 'MediaController@allVue',
        'as' => 'api.media.all-vue',
    ]);
    Route::get('media/index', [
        'uses' => 'MediaController@index',
        'as' => 'api.media.index',
    ]);
    Route::post('media/sort', [
        'uses' => 'MediaController@sortMedia',
        'as' => 'api.media.sort',
    ]);
    Route::get('media/find-first-by-zone-and-entity', [
        'uses' => 'MediaController@findFirstByZoneEntity',
        'as' => 'api.media.find-first-by-zone-and-entity',
    ]);

    Route::get('media/get-by-zone-and-entity', [
        'uses' => 'MediaController@getByZoneEntity',
        'as' => 'api.media.get-by-zone-and-entity',
    ]);

    Route::get('media/{file}', [
        'uses' => 'MediaController@find',
        'as' => 'api.media.find',
    ]);
    Route::put('file/{file}', [
        'uses' => 'MediaController@update',
        'as' => 'api.media.update',
    ]);
    Route::delete('file/{file}', [
        'uses' => 'MediaController@destroy',
        'as' => 'api.media.destroy',
    ]);

    Route::post('batch-destroy', [
        'uses' => 'BatchDestroyController',
        'as' => 'api.media.batch-destroy',
    ]);

    Route::post('editor', [
        'uses' => 'MediaController@storeEditor',
        'as' => 'api.media.editor.store',
    ]);
});
