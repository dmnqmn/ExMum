<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Photo;
use App\Models\UploadFile;

use Validator;

class PhotoController extends BaseController
{
    const SIZE = 8;
    const LAST_UPDATE_ID = 0;
    const TAG = 'Home feed';

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
            ->with('photo', Photo::getPhotoInfo($photo));
    }

    public function postNewPhoto(Request $request) {
        $validate = Validator::make($request->all(), [
            'title' => 'max:20',
            'description' => 'max:300',
            'tags' => 'array|required|max:5',
            'fileId' => 'required|integer'
        ], [
            'title.max' => 'PHOTO_NEW_TITLE_TOO_LANG',
            'description.max' => 'PHOTO_NEW_DESCRIPTION_TOO_LANG',
            'fileId.required' => 'PHOTO_NEW_FILE_ID_NEEDED',
            'fileId.integer' => 'PHOTO_NEW_FILE_ID_INTEGER',
            'tags.required' => 'PHOTO_NEW_TAGS_NEEDED',
            'tags.array' => 'PHOTO_NEW_TAGS_SHOULD_BE_ARRAY',
            'tags.max' => 'PHOTO_NEW_TAGS_TOO_LONG',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }

        $fileId = $request->input('fileId');
        $title = $request->input('title');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $uid = \Globals::$user->id;

        $uploadFile = UploadFile::find($fileId);
        if (is_null($uploadFile)) {
            return response()->json(['error' => 'PHOTO_NEW_FILE_NOT_FOUND'], 404);
        }

        if ($uploadFile->uid !== $uid) {
            return response()->json(['error' => 'PHOTO_NEW_FILE_FORBIDDEN'], 403);
        }

        $photo = Photo::create($uid, $fileId, $title, $description, $tags);
        $result = Photo::getPhotoInfo($photo);
        return response()->json($result);
    }
}
