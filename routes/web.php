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

/* router to the logged user dashbord */
Route::get('/user/dashboard')
    ->name('user.dashboard')
    ->middleware('auth')
    ->uses('UserController@displayUserHomePage');

/* router to the avatar creation form*/
Route::get('/addAvatar')
    ->name('addAvatar')
    ->middleware('auth')
    ->uses('UserController@displayAvatarCreationForm');

/* router to the avatar creation form (after registration)*/
Route::get('/addAvatarOnReg')
    ->name('addAvatarOnReg')
    ->middleware('auth')
    ->uses('UserController@displayAvatarCreationFormFromReg');

/* router to the process of avatar creation*/
Route::post('/addAvatar')
    ->name('addAvatar.process')
    ->uses('UserController@createNewAvatar');

/* router to the process of avatar deletion*/
Route::get('/removeAvatar/{id}')
    ->name('removeAvatar.process')
    ->middleware('auth')
    ->uses('UserController@deleteAvatar');

/* router to possibility of adding an avatar to a new created account*/
Route::get('/regAvatar')
    ->name('registration.avatar')
    ->middleware('auth')
    ->uses('UserController@displayRegistrationAvatarConfirmation');


Auth::routes();

Route::get('/home', 'HomeController@index');
