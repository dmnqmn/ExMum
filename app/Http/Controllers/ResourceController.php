<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Helper;
use App\Models\UploadFile;

use General;
use Config;

class ResourceController extends BaseController
{
    public function postUpload(Request $request) {
        $category = $request->category;
        $file = $request->file('file');
        $uploadFile = UploadFile::create($category, $file);
        $file->move(config('app.storage_path') . "/$category", $uploadFile->storage_name . $uploadFile->extension);
        return response()->json();
    }
}
