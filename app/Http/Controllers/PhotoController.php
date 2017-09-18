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
    const LAST_UPDATE_ID = 0;
    const TAG = 'Home feed';

    public function showPhotos(Request $request) {
        $lastUpdateId = $request->input('lastUpdateId');
        $size = $request->input('size');
        $tag = $request ->input('tag');
        $validate = Validator::make($request->all(), [
            'lastUpdateId' => 'integer',
            'size' => 'integer',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()], 400);
        }
        $lastUpdateId = is_null($lastUpdateId) ? self::LAST_UPDATE_ID : $lastUpdateId;
        $size = is_null($size) ? self::SIZE : $size;
        $tag = is_null($tag) ? self::TAG : $tag;
        $tags = explode(',' , $tag);
        $photos = Photo::takePhotoByTags($tags, $size, $lastUpdateId);
        $photo = end($photos);
        $lastUpdateId = $photo['id'];
        return [
            'lastUpdateId' => $lastUpdateId,
            'photos' => $photos,
            ];

    }

    public function getPhotoById($id) {
        $photo = Photo::getPhotoById($id);
        if (empty($photo)) {
            return response()->json(['error' => 'PHOTO_NOT_FOUND'], 404);
        }
        return $photo;
    }
}
