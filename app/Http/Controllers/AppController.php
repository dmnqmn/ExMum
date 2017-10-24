<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Helper;
use App\Models\Photo;

use General;
use Config;

class AppController extends BaseController
{
    public function getApp(Request $request) {
        return view('app')->with('jsVars', [
            ['user', \Globals::$user ? \Globals::$user->info() : null]
        ]);
    }

    public function getHot(Request $request) {
        $photos = Photo::getHot(20, 0);
        $lastPhoto = end($photos);
        $lastUpdateId = $lastPhoto ? $lastPhoto['id'] : null;
        $jsVars = [
            ['user', \Globals::$user],
            ['lastUpdateId', $lastUpdateId]
        ];
        return view('app')
            ->with('jsVars', $jsVars)
            ->with('photos', $photos);
    }
}
