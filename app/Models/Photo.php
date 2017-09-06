<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;
use DB;

class Photo extends Model
{
    protected $table = 'photo';

	public static function getPhotoByTags($tag, $page, $pagesize = 1) {
        $skip = 0;
        if ($page < 1) {
        	return false;
        }
        if ($page >= 2) {
            $skip = 2 + 3 * ($page - 2);
            $pagesize = 3;
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