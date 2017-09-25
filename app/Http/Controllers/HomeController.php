<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Helper;
use App\Models\Photo;

use General;
use Config;

class HomeController extends BaseController
{
    const HOME_TAG_NAME = 'Home feed'; // this value should be in an config file or database

    public function getHome(Request $request) {
        //
        $initPhotos = Photo::takePhotoByTags([self::HOME_TAG_NAME], 20, 0);
        $lastPhoto = end($initPhotos);
        $lastUpdateId = $lastPhoto ? $lastPhoto['id'] : null;

        $jsVars = [
            ['user', \Globals::$user],
            ['lastUpdateId', $lastUpdateId]
        ];

        return view('home')
            ->with('jsVars', $jsVars)
            ->with('photos', $initPhotos);
    }
}
