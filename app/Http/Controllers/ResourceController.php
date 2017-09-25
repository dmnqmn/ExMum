<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Helper;
use App\Models\UploadFile;

use General;
use Config;
use Validator;

class ResourceController extends BaseController
{
    public function postUploadImage(Request $request) {
        if ($error = $this->checkError($request, 'UPLOAD_IMAGE')) {
            return response()->json(['error' => $error], 400);
        }

        $file = $request->file('file');
        $uploadFile = UploadFile::create('image', \Globals::$user->id, $file);
        $uploadFile->saveFile();
        return response()->json(['id' => $uploadFile->id]);
    }

    public function postUploadAvatar(Request $request) {
        if ($error = $this->checkError($request, 'UPLOAD_AVATAR')) {
            return response()->json(['error' => $error], 400);
        }

        $file = $request->file('file');
        $uploadFile = UploadFile::create('avatar', \Globals::$user->id, $file);
        $uploadFile->saveFile();
        return response()->json(['id' => $uploadFile->id]);
    }

    function checkError($request, $apiPrefix) {
        $validate = Validator::make($request->all(), [
            'file' => 'required|file|max:20000'
        ], [
            'file.size' => $apiPrefix . '_TOO_LARGE',
            'file.required' => $apiPrefix . '_FILE_NEEDED',
            'file.file' => $apiPrefix . '_NOT_A_FILE'
        ]);

        if ($validate->fails()) {
            return $validate->messages()->first();
        }
    }
}
