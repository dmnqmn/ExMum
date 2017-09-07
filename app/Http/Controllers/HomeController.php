<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Helper;
use App\Models\Photo;


use General;
use Config;
use Validator;

class HomeController extends BaseController
{
    public function getHome(Request $request) {
        $jsVars = [
            ['user', \Globals::$user]
        ];

        //若要显示首页，将此处至return $url处注释即可；
        $page = $request->input('page');
        $validate = Validator::make($request->all(), [
            'page'   => 'required',
            ]);
        $uid = \Globals::$user['id'];
        $tags = User::selectTagsById($uid);
        $tags_arr = json_decode($tags, true);
        $tag = $tags_arr[0];
        $url = Photo::getPhotoByTags($tag, $page, $pagesize = 8);
        return $url;

        return view('home')
            ->with('jsVars', $jsVars)
            ->with('hotSearch', '热门');
    }
}
