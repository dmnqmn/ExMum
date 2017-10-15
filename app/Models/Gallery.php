<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;
use General;

class Gallery extends Model
{
    protected $table = 'gallery';

    public static function createGallery($param) {
    	$gallery = new Gallery;
    	$gallery->uid = $param['uid'];
    	$gallery->name = $param['name'];
    	$gallery->tag_id = $param['tag_id'];
    	$gallery->description = $param['description'];
    	$gallery->secret = $param['secret'];
    	$gallery->save();
    	return $gallery;
    }

    public static function deleteGallery($gallery_id) {
    	$gallery = Gallery::where('id', $gallery_id)->delete();
    	if ($gallery != 0) {
    		return $gallery;
    	}
    	return "DELETE_FAIlED";
    }
}
