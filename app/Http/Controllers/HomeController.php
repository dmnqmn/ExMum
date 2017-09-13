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

        return view('home')
            ->with('jsVars', $jsVars)
            ->with('hotSearch', '热门')
            ->with('photos', [
                    ['url' => 'http://img3.imgtn.bdimg.com/it/u=1401968824,2433090857&fm=11&gp=0.jpg'],
                    ['url' => 'http://img4.imgtn.bdimg.com/it/u=3325350060,2349764750&fm=11&gp=0.jpg'],
                    ['url' => 'http://img5.imgtn.bdimg.com/it/u=564796843,1812235672&fm=11&gp=0.jpg'],
                    ['url' => 'http://img2.imgtn.bdimg.com/it/u=1441884337,1838020747&fm=11&gp=0.jpg'],
                    ['url' => 'http://img3.imgtn.bdimg.com/it/u=1516891756,2194615571&fm=27&gp=0.jpg'],
                    ['url' => 'http://img2.imgtn.bdimg.com/it/u=2993321848,3135960404&fm=27&gp=0.jpg'],
                    ['url' => 'http://img4.imgtn.bdimg.com/it/u=1602552054,373587514&fm=27&gp=0.jpg'],
                    ['url' => 'http://img4.imgtn.bdimg.com/it/u=202447557,2967022603&fm=27&gp=0.jpg'],
                    ['url' => 'http://img3.imgtn.bdimg.com/it/u=3269061743,2028437678&fm=27&gp=0.jpg'],
                    ['url' => 'http://img1.imgtn.bdimg.com/it/u=3258213409,1470632782&fm=200&gp=0.jpg'],
                    ['url' => 'http://img1.imgtn.bdimg.com/it/u=982040384,1303124843&fm=200&gp=0.jpg'],
                    ['url' => 'http://img3.imgtn.bdimg.com/it/u=2626619975,2287398878&fm=200&gp=0.jpg'],
                    ['url' => 'http://img5.imgtn.bdimg.com/it/u=2598552500,296605635&fm=200&gp=0.jpg'],
                    ['url' => 'http://img1.imgtn.bdimg.com/it/u=585472287,1624777102&fm=200&gp=0.jpg'],
                    ['url' => 'http://img4.imgtn.bdimg.com/it/u=3704070695,1124292921&fm=200&gp=0.jpg'],
                    ['url' => 'http://img0.imgtn.bdimg.com/it/u=2384273948,595044370&fm=200&gp=0.jpg'],
                    ['url' => 'http://img5.imgtn.bdimg.com/it/u=1203727489,1880869354&fm=27&gp=0.jpg'],
                    ['url' => 'http://img2.imgtn.bdimg.com/it/u=1504686050,3733960510&fm=27&gp=0.jpg'],
                    ['url' => 'http://img2.imgtn.bdimg.com/it/u=1035436093,3470577695&fm=27&gp=0.jpg']
                ]);
    }

    public function showPhotos(Request $request) {
        $tag = $request->input('tag');
        $page = $request->input('page');
        $num = 1;
        $validate = Validator::make($request->all(), [
            'tag' => 'required',
            ]);
        if ($validate->fails()) {
            return response()->json(['error' => 'TAG_NEEDED!'], 400);
        }
        $url = Photo::showPhotoByTag($tag, $page, $pagesize = 8);
        $resUrl = [];
        foreach ($url as $key => $value) {
            $resUrl[] = $value['url'];
        }
        return [
            'url' => $resUrl,
            'isFollow' => $num,
        ];
    }
}
