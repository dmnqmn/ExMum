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
    public function getHome(Request $request) {
        $initPhotos = Photo::takePhotoByTags(config('tag.tags_id'), 20, 0);
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

    public function getHot(Request $request) {
        $photos = Photo::getHot(20, 0);
        $lastPhoto = end($photos);
        $lastUpdateId = $lastPhoto ? $lastPhoto['id'] : null;
        $jsVars = [
            ['user', \Globals::$user],
            ['lastUpdateId', $lastUpdateId]
        ];
        return view('home')
            ->with('jsVars', $jsVars)
            ->with('photos', $photos);
    }
}
