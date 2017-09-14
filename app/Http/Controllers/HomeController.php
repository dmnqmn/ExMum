<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Helper;

use General;
use Config;

class HomeController extends BaseController
{
    public function getHome(Request $request) {
        $jsVars = [
            ['user', \Globals::$user]
        ];
    return view('home')
        ->with('jsVars', $jsVars)
        ->with('hotSearch', '热门');
    }
}
