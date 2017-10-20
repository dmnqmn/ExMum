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

    Route::get('/gallery/{id}', 'GalleryController@getGallery')->name('gallery');
    Route::get('/gallery', 'GalleryController@getGallery');

    Route::get('/photos', 'PhotoController@showPhotos');
    Route::get('/photo/{id}', 'PhotoController@getPhotoById');

    Route::get('/user/following', 'UserController@getFollowing');
    Route::get('/user/followedBy', 'UserController@getFollowedBy');
    Route::post('/user/follow', 'UserController@postFollow');
    Route::post('/user/unfollow', 'UserController@postUnFollow');

    Route::get('/user/info', 'UserController@getUserInfo');
    Route::get('/user/{id}', 'UserController@getUserPage')->name('user');
    Route::get('/user/{id}/following', 'UserController@getUserFollowing');
    Route::get('/user/{id}/followedBy', 'UserController@getUserFollowedBy');

    Route::get('/activation', 'UserController@getActivation');
    Route::post('/register', 'UserController@postRegister');
    Route::post('/login', 'UserController@postLogin');
    Route::post('/logout', 'UserController@postLogout');

    Route::post('/gallery','GalleryController@postGallery');
    Route::delete('/gallery', 'GalleryController@deleteGallery');

    Route::get('/hot', 'HomeController@getHot');
};

Route::domain(env('APP_BASE_DOMAIN'))->group($mainRoutes);
Route::domain('www.' . env('APP_BASE_DOMAIN'))->group($mainRoutes);

Route::domain('resource.' . env('APP_BASE_DOMAIN'))->group(function () {
    Route::group(['middleware' => ['logined']], function () {
        Route::post('/upload/image', 'ResourceController@postUploadImage');
        Route::post('/upload/avatar', 'ResourceController@postUploadAvatar');
    });
});
