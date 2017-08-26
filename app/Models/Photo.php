<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;

class Photo extends Model
{
    protected $table = 'photo';
	
	public static function getPhotoByTags($tags) {
		$res = Photo::where($tags, 1)
					->select('url')
					->simplePaginate(3);
		return $res;

	}
}