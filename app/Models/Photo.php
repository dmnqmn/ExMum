<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;
use App\Models\PhotoTag;
use App\Models\Tag;

class Photo extends Model
{
    protected $table = 'photo';

    const DEFAULT_PAGE_SIZE = 8;

    // Relationships

    public function file() {
        return $this->hasOne('App\Models\UploadFile', 'id', 'file_id');
    }

    public function tags() {
        return $this->hasMany('App\Models\Tag', 'id', 'photo_id');
    }

    // Relationships end

    // Properties

    public function url() {
        $file = $this->file;
        return 'http://resource.' . config('app.base_domain') .
        "/static/$file->category/$file->storage_name.$file->extension";
    }

    // Properties end

    public static function create($uid, $file_id, $title, $description) {
        $photo = new Photo;
        $photo->uid = $uid;
        $photo->file_id = $file_id;
        $photo->title = $title;
        $photo->description = $description;
        $photo->save();
        return $photo;
    }

    public static function takePhotoByTags($tags, $size = self::DEFAULT_PAGE_SIZE, $lastUpdateId) {
        $photoTags = PhotoTag::whereIn('tag_id', $tags);

        if (is_int($lastUpdateId) && $lastUpdateId > 0) {
            $photoTags = $photoTags->where('photo_id', '<', $lastUpdateId);
        }
        $photoTags = $photoTags->orderBy('created_at', 'desc')
                               ->take($size)
                               ->select('photo_id')
                               ->groupBy('photo_id')
                               ->get();

        if (empty($photoTags)) {
            return [];
        }

        $res = [];
        foreach ($photoTags as $k => $photoTag) {
            $photo = $photoTag->photo;
            $res[$k]['createTime'] = $photo->created_at;
            $res[$k]['id'] = $photo->id;
            $res[$k]['url'] = $photo->url();
            $res[$k]['name'] = $photo->name;
            $res[$k]['description'] = $photo->description;
            $res[$k]['tags'] = $photo->tags;
        }
        return $res;
    }

    public static function getPhotoById($id) {
        $photo = Photo::where('id', $id)
                      ->get()
                      ->first();
        return $photo;
    }
}
