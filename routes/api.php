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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function() {
    Route::get('/requestItem/list', 'RequestItemController@list');
    Route::post('/requestItem/store', 'RequestItemController@store');
    Route::delete('/requestItem/remove', 'RequestItemController@remove');
    Route::post('/upload', 'UploadController@upload');
    Route::get('/download/{s3_key}', 'UploadController@get');
    Route::delete('/remove/{s3_key}', 'UploadController@remove');

    Route::get('/comment/list', 'CommentController@list');
    Route::post('/comment/store', 'CommentController@store');
    Route::delete('/comment/remove', 'CommentController@remove');
    Route::get('/requestItem/get', 'RequestItemController@get');
});
