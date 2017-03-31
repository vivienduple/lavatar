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

/* router to the sign-in form*/
Route::get('/login')
    ->name('login')
    ->uses('Auth//LoginController@displayLoginForm');

/* router to the process of the sign-in form*/
Route::post('/login')
    ->name('signIn');

/* router to the sign-up form*/
Route::get('/register')
    ->name('register')
    ->uses('Auth//RegisterController@displayRegisterForm');

/* router to the process of the sign-up form*/
Route::post('/register')
    ->name('signUp');

/* router to user {id} home page*/
Route::get('/user/{id}')
    ->name('user')
    ->where('id','[0-9]+')
    ->uses('UserController@displayUserHomePage');

/* router to the avatar creation form*/
Route::get('/addAvatar')
    ->name('addAvatar')
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
