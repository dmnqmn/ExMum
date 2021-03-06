<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UploadFile extends Model
{
    protected $file;
    protected $table = 'upload_file';

    // Properties

    public function url() {
        return 'http://resource.' . config('app.base_domain') .
        "/static/$this->category/$this->storage_name.$this->extension";
    }

    // Properties end

    public static function create($category, $uid, $file) {
        $uploadFile = new UploadFile;
        $originalFullName = $file->getClientOriginalName();
        $originalExtension = $file->getClientOriginalExtension();
        $originalName = preg_replace("/\\.$originalExtension$/", '', $originalFullName);
        $uploadFile->category = $category;
        $uploadFile->file = $file;
        $uploadFile->uid = $uid;
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

    public function saveFile() {
        $this->file->move(
            config('app.storage_path') . "/$this->category",
            "$this->storage_name.$this->extension"
        );
    }
}
