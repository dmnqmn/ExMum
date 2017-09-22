<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';

    public static function create($creater_uid, $name, $description = null) {
        $tag = new Tag;
        $tag->creater_uid = $creater_uid;
        $tag->name = $name;
        $tag->description = $description;
        $tag->save();
    }
}
