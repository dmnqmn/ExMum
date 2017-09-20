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

$mainRoutes = function () {
    Route::get('/', 'HomeController@getHome')->name('home');
    Route::get('/settings', 'SettingController@getSettings')->name('settings');

    Route::get('/photos', 'PhotoController@showPhotos');
    Route::get('/photo/{id}', 'PhotoController@getPhotoById');

    Route::get('/user/info', 'UserController@getInfoByUid');
    Route::post('/user/info', 'UserController@postUserInfo');

    Route::get('/activation', 'UserController@getActivation');
    Route::post('/register', 'UserController@postRegister');
    Route::post('/login', 'UserController@postLogin');
    Route::post('/changePwd', 'UserController@postChangePwd');
};

Route::domain(env('APP_BASE_DOMAIN'))->group($mainRoutes);
Route::domain('www.' . env('APP_BASE_DOMAIN'))->group($mainRoutes);

Route::domain('resource.' . env('APP_BASE_DOMAIN'))->group(function () {
    Route::post('/upload', 'ResourceController@postUpload');
});
