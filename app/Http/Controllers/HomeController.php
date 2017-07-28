<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Helper;

use General;
use Config;
use Validator;

class HomeController extends BaseController
{
    public function getHome(Request $request) {
        return view('home')->with('hotSearch', '热门');
    }
}
