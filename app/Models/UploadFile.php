<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UploadFile extends Model
{
    protected $file;
    protected $table = 'upload_file';

    public static function create($category, $file) {
        $uploadFile = new UploadFile;
        $originalFullName = $file->getClientOriginalName();
        $originalExtension = $file->getClientOriginalExtension();
        $originalName = preg_replace("/\\.$originalExtension$/", '', $originalFullName);
        $uploadFile->category = $category;
        $uploadFile->file = $file;
        $uploadFile->original_name = substr($originalName, 0, 50);
        $uploadFile->mimetype = $file->getMimeType();
        $uploadFile->extension = $file->guessExtension();
        $uploadFile->size = filesize($file->path());
        $uploadFile->save();
        $uploadFile->storage_name = $uploadFile->generateStorageName();
        $uploadFile->save();
        return $uploadFile;
    }

    function generateStorageName() {
        $contentHash = md5_file($this->file->path());
        $idHash = substr(md5($contentHash . $this->id . time()), 0, 39);
        return "$idHash-" . time();
    }
}
