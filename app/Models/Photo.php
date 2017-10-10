<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;

class Photo extends Model
{
    protected $table = 'photo';

    const PAGE = 2;
    const PAGE_SIZE = 10;
    const DEFAULT_SIZE = 8;

    const TAGS_ID_NAME  = [
        'tag1'  => 'Home feed',
        'tag2'  => 'Popular',
        'tag3'  => 'Everything',
        'tag4'  => 'Gifts',
        'tag5'  => 'Videos',
        'tag6'  => 'Animals and pets',
        'tag7'  => 'Architecture',
        'tag8'  => 'Art',
        'tag9'  => 'Cars and motocyles',
        'tag10' => 'Celebrities',
        'tag11' => 'DIY and crafts',
        'tag12' => 'Design',
        'tag13' => 'Education',
        'tag14' => 'Entertainment',
        'tag15' => 'Food and drink',
        'tag16' => 'Gardening',
        'tag17' => 'Geek',
        'tag18' => 'Hair and beauty',
        'tag19' => 'Health and fitness',
        'tag20' => 'History',
        'tag21' => 'Holidays and events',
        'tag22' => 'Humor',
    ];

    const TAGS_NAME_ID  = [
        'Home feed' => 'tag1',
        'Popular' => 'tag2',
        'Everything' => 'tag3',
        'Gifts' => 'tag4',
        'Videos' => 'tag5',
        'Animals and pets' => 'tag6',
        'Architecture' => 'tag7',
        'Art' => 'tag8',
        'Cars and motocyles' => 'tag9',
        'Celebrities' => 'tag10',
        'DIY and crafts' => 'tag11',
        'Design' => 'tag12',
        'Education' => 'tag13',
        'Entertainment' => 'tag14',
        'Food and drink' => 'tag15',
        'Gardening' => 'tag16',
        'Geek' => 'tag17',
        'Hair and beauty' => 'tag18',
        'Health and fitness' => 'tag19',
        'History' => 'tag20',
        'Holidays and events' => 'tag21',
        'Humor' => 'tag22',
    ];

    // Relationships

    public function file() {
        return $this->hasOne('App\Models\UploadFile', 'id', 'file_id');
    }

    public function author() {
        return $this->hasOne('App\Models\User', 'id', 'uid');
    }

    // Relationships end

    public static function create($uid, $file_id, $title, $description, $tags) {
        $photo = new Photo;
        $photo->uid = $uid;
        $photo->file_id = $file_id;
        $photo->title = $title;
        $photo->description = $description;

        foreach ($tags as $tagName) {
            $tagId = self::TAGS_NAME_ID[$tagName];
            if (!is_null($tagId)) {
                $photo->$tagId = 1;
            }
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
        foreach ($tags as $v) {
            if (!in_array($v, self::TAGS_ID_NAME)) {
                return [];
            }
            $tag[] = array_search($v, self::TAGS_ID_NAME);
        }
        $photos = static::take($size);
        if ($lastUpdateId > 0) {
            $photos = $photos->where('id', '<', $lastUpdateId);
        }
        $photos = $photos->where(function ($query) use ($tag) {
                            foreach ($tag as $k => $v) {
                                if ($k == 0) {
                                    $query->where($v, 1);
                                } else {
                                    $query->orWhere($v, 1);
                                }
                            }
                        }, $tag)->orderBy('id', 'desc')
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
        foreach ($photo as $k => $v) {
            if (array_key_exists($k, self::TAGS_ID_NAME) && $v === 1) {
                $res[] = self::TAGS_ID_NAME[$k];
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
            'author' => $photo->author
        ];
    }
}
