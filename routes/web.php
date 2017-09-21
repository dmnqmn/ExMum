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
        Route::get('/photo/new', 'PhotoController@getNewPhoto');
    });

    Route::get('/photos', 'PhotoController@showPhotos');
    Route::get('/photo/{id}', 'PhotoController@getPhotoById');

    Route::get('/user/info', 'UserController@getInfoByUid');

    Route::get('/activation', 'UserController@getActivation');
    Route::post('/register', 'UserController@postRegister');
    Route::post('/login', 'UserController@postLogin');

};

Route::domain(env('APP_BASE_DOMAIN'))->group($mainRoutes);
Route::domain('www.' . env('APP_BASE_DOMAIN'))->group($mainRoutes);

Route::domain('resource.' . env('APP_BASE_DOMAIN'))->group(function () {
    Route::post('/upload/image', 'ResourceController@postUploadImage');
    Route::post('/upload/avatar', 'ResourceController@postUploadAvatar');
});
