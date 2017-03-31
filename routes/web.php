<?php

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

Route::get('/', function () {
    return view('welcome');
});

/* router to user {id} home page*/
Route::get('/user/dashboard')
    ->name('user.dashboard')
    ->middleware('auth')
    ->uses('UserController@displayUserHomePage');

/* router to the avatar creation form*/
Route::get('/addAvatar')
    ->name('addAvatar')
    //->middleware('auth')
    ->uses('UserController@displayAvatarCreationForm');

/* router to the process of avatar creation form*/
Route::post('/addAvatar')
    ->name('addAvatar.process')
    ->uses('UserController@createNewAvatar');

/* router to the process of avatar deletion*/
Route::post('/removeAvatar')
    ->name('removeAvatar.process')
    ->uses('UserController@deleteAvatar');




Auth::routes();

Route::get('/home', 'HomeController@index');
