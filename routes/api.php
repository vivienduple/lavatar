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


/* the route to get a image avatar in a given format, and associated to the given email*/
Route::get('avatar/{email}/{size?}')
    ->name('api.avatar')
    ->where('size','[1,2]{1}')
    ->uses('ApiController@provideAvatarFromEmail');

/* the route to get data about an avatar associated to the given email, in the requested format*/
Route::get('request/avatar/{email}/{size?}')
    ->name('api.request.avatar')
    ->where('size','[1,2]{1}')
    ->uses('ApiController@provideAvatarInfos');

/* the route to get data about the application */
Route::get('appData')
    ->name('api.data')
    ->uses('ApiController@provideAppData');











