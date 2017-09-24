<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoTag extends Model
{
    protected $table = 'photo_tag';

    // Relationships

    public function photo() {
        return $this->hasOne('App\Models\Photo', 'id', 'photo_id');
    }

    public function tag() {
        return $this->hasOne('App\Models\Tag', 'id', 'tag_id');
    }

    // Relationships end

    public static function create($photo_id, $tag_id) {
        $photoTag = new PhotoTag;
        $photoTag->photo_id = $photo_id;
        $photoTag->tag_id = $tag_id;
        $photoTag->save();
    }
}
