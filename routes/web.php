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

Route::get('/', 'HomeController@getHome')->name('home');
Route::get('/photos', 'PhotoController@showPhotos');

Route::get('/settings', 'UserController@getSettings');
Route::get('/activation', 'UserController@getActivation');

Route::post('/register', 'UserController@postRegister');
Route::post('/login', 'UserController@postLogin');
Route::post('/changePwd', 'UserController@postChangePwd');
Route::post('/user/set', 'UserController@postSettings');