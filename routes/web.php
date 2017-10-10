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

    Route::group(['middleware' => ['logined']], function () {
        Route::post('/changePwd', 'UserController@postChangePwd');
        Route::get('/settings', 'SettingController@getSettings')->name('settings');
        Route::post('/user/info', 'UserController@postUserInfo');
        Route::post('/photo/new', 'PhotoController@postNewPhoto');
    });

    Route::get('/photos', 'PhotoController@showPhotos');
    Route::get('/photo/{id}', 'PhotoController@getPhotoById');

    Route::get('/user/info', 'UserController@getUserInfo');
    Route::get('/user/{id}', 'UserController@getUserPage')->name('user');

    Route::get('/activation', 'UserController@getActivation');
    Route::post('/register', 'UserController@postRegister');
    Route::post('/login', 'UserController@postLogin');

    Route::post('/user/follow', 'UserController@postFollow');
    Route::post('/user/unfollow', 'UserController@postUnFollow');
    Route::get('/user/follow', 'UserController@getFollow');
};

Route::domain(env('APP_BASE_DOMAIN'))->group($mainRoutes);
Route::domain('www.' . env('APP_BASE_DOMAIN'))->group($mainRoutes);

Route::domain('resource.' . env('APP_BASE_DOMAIN'))->group(function () {
    Route::group(['middleware' => ['logined']], function () {
        Route::post('/upload/image', 'ResourceController@postUploadImage');
        Route::post('/upload/avatar', 'ResourceController@postUploadAvatar');
    });
});
