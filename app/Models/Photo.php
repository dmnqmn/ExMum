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

}