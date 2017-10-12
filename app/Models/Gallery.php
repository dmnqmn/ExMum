<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;
use General;

class Gallery extends Model
{
    protected $table = 'gallery';

    public static function createGallery($tag_id, $uid, $name, $description, $secret) {
    	$gallery = new Gallery;
    	$gallery->tag_id = $tag_id;
    	$gallery->uid = $uid;
    	$gallery->name = $name;
    	$gallery->description = $description;
    	$gallery->secret = $secret;
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