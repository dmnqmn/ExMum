<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;
use Config;

class Photo extends Model
{
    protected $table = 'photo';

    const PAGE = 2;
    const PAGE_SIZE = 10;
    const DEFAULT_SIZE = 8;

    // Relationships

    public function file() {
        return $this->hasOne('App\Models\UploadFile', 'id', 'file_id');
    }

    public function author() {
        return $this->hasOne('App\Models\User', 'id', 'uid');
    }

    public function gallery() {
        return $this->hasOne('App\Models\Gallery', 'id', 'gallery_id');
    }

    // Relationships end

    public static function create($uid, $file_id, $title, $description, $tags, $gallery_id, $original_id = null) {
        $photo = new Photo;
        $photo->uid = $uid;
        $photo->file_id = $file_id;
        $photo->title = $title;
        $photo->description = $description;
        $photo->gallery_id = $gallery_id;
        $photo->original_id = $original_id;
        foreach ($tags as $tagId) {
            $tag = "tag$tagId";
            $photo->$tag = 1;
        }

        $photo->save();
        return $photo;
    }

	public static function getPhotoByTags($tag, $page, $pagesize = 1) {
        $skip = 0;
        if ($page < 1) {
        	return false;
        }
        if ($page >= self::PAGE) {
            $skip = self::DEFAULT_SIZE + self::PAGE_SIZE * ($page - self::PAGE);
            $pagesize = self::PAGE_SIZE;
        }
        $res = static::where($tag, 1)
                     ->skip($skip)
                     ->take($pagesize)
                     ->select('url')
                     ->get()
                     ->toArray();
        if (! $res) {
            return [];
        }
        return $res;
    }

    public static function takePhotoByTags($tags, $size, $lastUpdateId) {
        $photos = static::take($size);
        if ($lastUpdateId > 0) {
            $photos = $photos->where('id', '<', $lastUpdateId);
        }
        $photos = $photos->with(['author', 'gallery', 'file'])
                        ->where(function ($query) use ($tags) {
                            foreach ($tags as $k => $v) {
                                if ($k == 0) {
                                    $query->where("tag$v", 1);
                                } else {
                                    $query->orWhere("tag$v", 1);
                                }
                            }
                        }, $tags)
                        ->orderBy('id', 'desc')
                        ->get();
        if (empty($photos)) {
            return [];
        }
        $res = [];
        foreach ($photos as $k => $photo) {
            $res[$k] = self::getPhotoInfo($photo);
        }
        return $res;
    }

    public static function handleTags($photo) {
        $res = [];
        $photo = $photo->toArray();
        $tags_id_name = Config::get('tag.tags_id_name');
        foreach ($photo as $k => $v) {
            if (array_key_exists($k, $tags_id_name) && $v === 1) {
                $res[] = $tags_id_name[$k];
            }
        }
        return $res;
    }

    public static function getPhotoById($id) {
        $photo = Photo::where('id', $id)
                      ->get()
                      ->first();
        return $photo;
    }

    public static function getPhotoInfo($photo) {
        return [
            'id' => $photo->id,
            'url' => $photo->file->url(),
            'created_at' => $photo->created_at,
            'title' => $photo->title,
            'description' => $photo->description,
            'tags' => self::handleTags($photo),
            'author' => $photo->author->publicInfo(),
            'gallery' => $photo->gallery,
        ];
    }

    public static function getHot($size, $lastUpdateId) {
        $photos = static::take($size);
        if ($lastUpdateId > 0) {
            $photos = $photos->where('id', '<', $lastUpdateId);
        }
        $photos = $photos->orderBy('id', 'desc')
                         ->get();
        if (empty($photos)) {
            return [];
        }
        $res = [];
        foreach ($photos as $k => $photo) {
            $res[$k] = self::getPhotoInfo($photo);
        }
        return $res;
    }
}
