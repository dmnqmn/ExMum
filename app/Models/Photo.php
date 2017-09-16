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

    const TAGS_CONF  = [
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
            if (!in_array($v, self::TAGS_CONF)) {
                return [];  
            }
            $tag[] = array_search($v, self::TAGS_CONF); 
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
                                ->get()
                                ->toArray();
        if (empty($photos)) {
            return [];
        }
        $res = [];
        foreach ($photos as $k => $photo) { 
            $res[$k]['createTime'] = $photo['created_at'];
            $res[$k]['id'] = $photo['id'];
            $res[$k]['url'] = $photo['url'];
            $res[$k]['tags'] = self::handleTags($photo);
        }
        return $res;
    }

    public static function handleTags($photo) {
        $res = [];
        foreach ($photo as $k => $v) {
            if (array_key_exists($k, self::TAGS_CONF) && $v == 1) {
                $res[] = self::TAGS_CONF[$k];
            }
        }
        return $res;
    }
}