<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Photo;

use Validator;

class photoController extends BaseController
{
    const SIZE = 8;
    const LASTUPDATEID = 0;
    const TAG = 'Home feed';
    
	public function getPhotos(Request $request) {
		$jsVars = [
            ['user', \Globals::$user]
        ];
        $page = $request->input('page');
        $validate = Validator::make($request->all(), [
            'page' => 'required',
            ]);
        if ($validate->fails()) {
            return response()->json(['error' => 'wrong messages!'], 400);
        }
        $uid = \Globals::$user['id'];
        $tags = User::selectTagsById($uid);
        $tags_arr = json_decode($tags, true);
        $tag = $tags_arr[0];
        $url = Photo::getPhotoByTags($tag = 'tag1', $page = 1, $pagesize = 8);
        return $url;
	}

    public function showPhotos(Request $request) {
        $tag = $request->input('tag');
        $size = $request->input('size');
        $lastUpdateId = $request->input('lastUpdateId');
        $validate = Validator::make($request->all(), [
            'size' => 'integer', 
            'lastUpdateId' => 'integer',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()], 400);
        }
        $size = is_null($size) ? self::SIZE : $size;
        $lastUpdateId = is_null($lastUpdateId) ? self::LASTUPDATEID : $lastUpdateId;
        $tag = is_null($tag) ? self::TAG : $tag;
        $tags = explode(',', $tag);
        $photos = Photo::getPhotosByTags($tags, $lastUpdateId, $size);
        $lastPhoto = end($photos);
        $userFollow = Photo::userFollowed(1);
        return [
            'lastUpdateId' => $lastPhoto['id'],
            'photos' => $photos,
            'tags' => $userFollow,
        ];
    }
}