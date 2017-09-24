<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Photo;
use App\Models\PhotoTag;
use App\Models\UploadFile;

use Validator;

class PhotoController extends BaseController
{
    const SIZE = 8;
    const DEFAULT_TAG_ID = 1; // should be in config file or database
    const LAST_UPDATE_ID = 0;

    public function showPhotos(Request $request) {
        $lastUpdateId = $request->input('lastUpdateId');
        if (is_null($lastUpdateId)) {
            return [];
        }
        $size = $request->input('size');
        $tag = $request ->input('tag');
        $validate = Validator::make($request->all(), [
            'lastUpdateId' => 'integer',
            'size' => 'integer',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()], 400);
        }
        $lastUpdateId = is_null($lastUpdateId) ? self::LAST_UPDATE_ID : intval($lastUpdateId);
        $size = is_null($size) ? self::SIZE : $size;
        $tag = is_null($tag) ? self::DEFAULT_TAG_ID : $tag;
        $tags = explode(',' , $tag);
        $photos = Photo::takePhotoByTags($tags, $size, $lastUpdateId);
        $photo = end($photos);
        $lastUpdateId = $photo['id'];
        return [
            'lastUpdateId' => $lastUpdateId,
            'photos' => $photos,
        ];
    }

    public function getPhotoById(Request $request, $id) {
        // Ajax request get json response
        if ($request->ajax()) {
            $photo = Photo::getPhotoById($id);
            if (empty($photo)) {
                return response()->json(['error' => 'PHOTO_NOT_FOUND'], 404);
            }
            return $photo;
        }

        // Get a html page if request is not ajax
        $jsVars = [
            ['user', \Globals::$user]
        ];

        $photo = Photo::getPhotoById($id);

        if (is_null($photo)) {
            abort(404);
        }

        return view('photo')
            ->with('jsVars', $jsVars)
            ->with('photo', $photo);
    }

    public function getNewPhoto(Request $request) {
        $jsVars = [
            ['user', \Globals::$user]
        ];

        return view('photoNew')->with('jsVars', $jsVars);
    }

    public function postNewPhoto(Request $request) {
        $validate = Validator::make($request->all(), [
            'title' => 'max:20',
            'description' => 'max:300',
            'file_id' => 'required|integer',
            'tags' => 'required|array'
        ], [
            'title.max' => 'PHOTO_NEW_TITLE_TOO_LANG',
            'description.max' => 'PHOTO_NEW_DESCRIPTION_TOO_LANG',
            'file_id.required' => 'PHOTO_NEW_FILE_ID_NEEDED',
            'file_id.integer' => 'PHOTO_NEW_FILE_ID_INTEGER',
            'tags.array' => 'PHOTO_NEW_TAGS_MUST_BE_ARRAY',
            'tags.required' => 'PHOTO_NEW_TAGS_NEEDED'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }

        $file_id = $request->input('file_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $uid = \Globals::$user->id;

        $uploadFile = UploadFile::find($file_id);
        if (is_null($uploadFile)) {
            return response()->json(['error' => 'PHOTO_NEW_FILE_NOT_FOUND'], 404);
        }

        if ($uploadFile->uid !== $uid) {
            return response()->json(['error' => 'PHOTO_NEW_FILE_FORBIDDEN'], 403);
        }

        if (count($tags) > 5) {
            return response()->json(['error' => 'PHOTO_NEW_TAGS_TOO_MANY'], 400);
        }

        $photo = Photo::create($uid, $file_id, $title, $description);
        $photoId = $photo->id;

        collect($tags)->unique()->each(function ($tagId) use ($photoId) {
            PhotoTag::create($photoId, $tagId);
        });

        return response()->json($photo);
    }
}
