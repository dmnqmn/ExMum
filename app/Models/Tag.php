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

    public static function getAllTags() {
        if (\App::environment('APP_ENV', 'production')) {
            return Cache::remember('global_tags', 1, function () {
                return static::select('id', 'name', 'description')->get();
            });
        }

        return static::select('id', 'name', 'description')->get();
    }
}
