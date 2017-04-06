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


/* api to get data about provided avatar images*/
/*Route::get('infos')
    ->name('api.infos')
    ->uses('ApiController@provideAvatarInfos');*/

/* api to get avatar*/
Route::get('avatar/{email}/{size?}')
    ->name('api.avatar')
    ->where('size','[1,2]{1}')
    ->uses('ApiController@provideAvatarFromEmail');

/* api to get avatar*/
Route::get('request/avatar/{email}/{size?}')
    ->name('api.request.avatar')
    ->where('size','[1,2]{1}')
    ->uses('ApiController@provideAvatarInfos');











