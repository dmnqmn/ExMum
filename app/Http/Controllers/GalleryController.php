<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\User;
use App\Models\Gallery;

use Validator;

class GalleryController extends BaseController {

	public function postGallery(Request $request) {
		$tag_id = $request->input('tag_id');
		$uid = \Globals::$user->id;
		$name = $request->input('name');
		$description = $request->input('description');
		$secret = $request->input('secret');
		$validate = Validator::make($request->all(), [
			'tag_id' => 'required|integer',
			'name' => 'required|max:10',
			'description' => 'nullable|max:255',
			'secret' => 'required|integer|between:0,1',
		],[
			'tag_id.required' => 'GALLERY_TAG_ID_NEEDED',
			'name.required' => 'GALLERY_NAME_NEEDED',
			'name.max' => 'GALLERY_NAME_TOO_LONG',
			'description.max' => 'GALLERY_DESCRIPTION_TOO_LONG',
			'secret.required' => 'GALLERY_SECERT_NEEDED',
			'secret.between'  => 'GALLERY_SECERT_INVALID',
		]);
		if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        $param = [
        	'uid' => $uid,
        	'name' => $name,
        	'tag_id' => $tag_id,
        	'description' => $description,
        	'secret' => $secret,
        ];
        $res = Gallery::createGallery($param);
        if ($res == true) {
            return response()->json();
        }
        return response()->json(['error' => $res], 403);
	}

	public function deleteGallery(Request $request) {
		$gallery_id = $request->input('gallery_id');
		$res = Gallery::deleteGallery($gallery_id);
		if ($res != 0) {
			return response()->json();
		}
		return response()->json(['error' => $res], 403);
	}
}