<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;

class Photo extends Model
{
    protected $table = 'photo';

    const PAGE = 2;
    const PAGESIZE = 10;
    const DEFAULTSIZE = 8;

    const TAGSCONF  = [
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

	public static function getPhotoByTags($tag, $page, $pagesize = 1) {
        $skip = 0;
        if ($page < 1) {
        	return false;
        }
        if ($page >= self::PAGE) {
            $skip = self::DEFAULTSIZE + self::PAGESIZE * ($page - self::PAGE);
            $pagesize = self::PAGESIZE;
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

    public static function showPhotoByTag($tag, $page, $pagesize) {
        $skip = 0;
        if ($page < 1) {
            return false;
        }
        if ($page >= self::PAGE) {
            $skip = self::DEFAULTSIZE + self::PAGESIZE * ($page - self::PAGE);
            $pagesize = self::PAGESIZE;
        }
        $res = static::where('tag'.$tag, 1)
                     ->skip($skip)
                     ->take($pagesize)
                     ->select('url')
                     ->get()
                     ->toArray();
        return $res;

    }

    public static function getPhotosByTags($tags, $lastUpdateId, $size) {
        $tag = array_search(head($tags), self::TAGSCONF);
        $photos = static::where($tag, 1)
                    ->take($size)
                    ->orderBy('id', 'desc')
                    ->select('url', 'id', 'created_at as createTime')
                    ->get()
                    ->toArray();
        return $photos;
    }

    public static function userFollowed($uid) {
        $userInfo = User::where('id', $uid)->first();
        $res = [];
        if (is_null($userInfo)) {
            foreach (self::TAGSCONF as $v) {
                $res[] = ['tag' => $v, 'followed' => false];
            }
        } else {
            $tags = $userInfo->tags;
            $tagsArr = explode(',', $tags);
            foreach (self::TAGSCONF as $k => $v) {
                $followed = false;
                if (in_array($k, $tagsArr)) {
                    $followed = true;
                }
                $res[] = ['tag' => $v, 'followed' => $followed];
            }
        }
        return $res;
    }
}